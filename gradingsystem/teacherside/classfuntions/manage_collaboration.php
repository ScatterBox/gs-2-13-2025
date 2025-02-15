<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../login.php");
    exit();
}

$loggedInUserId = $_SESSION['user']['user_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradingsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create teacher_collaborations table if it doesn't exist
$createTableSQL = "CREATE TABLE IF NOT EXISTS teacher_collaborations (
    collaboration_id INT PRIMARY KEY AUTO_INCREMENT,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    added_by INT NOT NULL,
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(user_id),
    FOREIGN KEY (added_by) REFERENCES teachers(user_id),
    UNIQUE KEY unique_collaboration (subject_id, teacher_id)
)";
$conn->query($createTableSQL);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = ["success" => false, "message" => ""];
    
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $subject_id = $_POST['subject_id'];
                $teacher_id = $_POST['teacher_id'];
                
                // Check if teacher exists
                $checkTeacher = $conn->prepare("SELECT user_id FROM teachers WHERE user_id = ?");
                $checkTeacher->bind_param("i", $teacher_id);
                $checkTeacher->execute();
                if ($checkTeacher->get_result()->num_rows === 0) {
                    $response["message"] = "Selected teacher does not exist.";
                    break;
                }
                
                // Check if subject exists and is owned by logged-in teacher
                $checkSubject = $conn->prepare("SELECT subject_id FROM subjects WHERE subject_id = ? AND created_by = ?");
                $checkSubject->bind_param("ii", $subject_id, $loggedInUserId);
                $checkSubject->execute();
                if ($checkSubject->get_result()->num_rows === 0) {
                    $response["message"] = "You don't have permission to add collaborators to this subject.";
                    break;
                }
                
                // Add collaboration
                $stmt = $conn->prepare("INSERT INTO teacher_collaborations (subject_id, teacher_id, added_by) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $subject_id, $teacher_id, $loggedInUserId);
                
                if ($stmt->execute()) {
                    $response["success"] = true;
                    $response["message"] = "Teacher successfully added as collaborator.";
                } else {
                    if ($conn->errno == 1062) {
                        $response["message"] = "This teacher is already a collaborator for this subject.";
                    } else {
                        $response["message"] = "Error adding collaborator: " . $conn->error;
                    }
                }
                break;
                
            case 'remove':
                $collaboration_id = $_POST['collaboration_id'];
                
                // Check if the collaboration exists and was added by the logged-in teacher
                $checkCollab = $conn->prepare("SELECT c.* FROM teacher_collaborations c 
                                             JOIN subjects s ON c.subject_id = s.subject_id 
                                             WHERE c.collaboration_id = ? AND s.created_by = ?");
                $checkCollab->bind_param("ii", $collaboration_id, $loggedInUserId);
                $checkCollab->execute();
                
                if ($checkCollab->get_result()->num_rows === 0) {
                    $response["message"] = "You don't have permission to remove this collaborator.";
                    break;
                }
                
                // Remove collaboration
                $stmt = $conn->prepare("DELETE FROM teacher_collaborations WHERE collaboration_id = ?");
                $stmt->bind_param("i", $collaboration_id);
                
                if ($stmt->execute()) {
                    $response["success"] = true;
                    $response["message"] = "Collaborator successfully removed.";
                } else {
                    $response["message"] = "Error removing collaborator: " . $conn->error;
                }
                break;
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Fetch all teachers except the logged-in user
$teachersSql = "SELECT user_id, CONCAT(fname, ' ', mname, ' ', lname, ' ', COALESCE(ename, '')) as full_name 
                FROM teachers WHERE user_id != ? ORDER BY fname";
$teachersStmt = $conn->prepare($teachersSql);
$teachersStmt->bind_param("i", $loggedInUserId);
$teachersStmt->execute();
$teachersResult = $teachersStmt->get_result();

// Fetch subjects created by the logged-in teacher
$subjectsSql = "SELECT subject_id, subject_name, year_level, section, school_year 
                FROM subjects WHERE created_by = ? ORDER BY year_level, section, subject_name";
$subjectsStmt = $conn->prepare($subjectsSql);
$subjectsStmt->bind_param("i", $loggedInUserId);
$subjectsStmt->execute();
$subjectsResult = $subjectsStmt->get_result();

// Fetch existing collaborations for the logged-in teacher's subjects
$collabsSql = "SELECT c.collaboration_id, c.subject_id, c.teacher_id, 
                      CONCAT(t.fname, ' ', t.mname, ' ', t.lname, ' ', COALESCE(t.ename, '')) as teacher_name,
                      s.subject_name, s.year_level, s.section, s.school_year
               FROM teacher_collaborations c
               JOIN teachers t ON c.teacher_id = t.user_id
               JOIN subjects s ON c.subject_id = s.subject_id
               WHERE s.created_by = ?
               ORDER BY s.year_level, s.section, s.subject_name";
$collabsStmt = $conn->prepare($collabsSql);
$collabsStmt->bind_param("i", $loggedInUserId);
$collabsStmt->execute();
$collabsResult = $collabsStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subject Collaborations</title>
    
    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

    <!-- Include Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Include DataTables CSS -->
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.css" rel="stylesheet">

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>

    <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Include SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../styles/style1.css">
    
    <style>
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .collaborators-list {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../images/logo.jpg" alt="logo" />
                <div class="header-text">
                    <h2 class="dashboard-title">
                        <a href="../teacher.php" class="dashboard-link">
                            <span class="admin-text">Teacher</span>
                            <span class="dashboard-text">Dashboard</span>
                        </a>
                    </h2>
                </div>
            </div>
            <ul class="sidebar-links">
                <li>
                    <a href="classcreate.php">
                        <i class="fa-solid fa-chalkboard"></i> Create Class
                    </a>
                </li>
                <li>
                    <a href="classlist.php">
                        <i class="fa-solid fa-book"></i> Subjects List
                    </a>
                </li>
                <li>
                    <a href="mystudents.php">
                        <i class="fa-solid fa-user-graduate"></i> My Students
                    </a>
                </li>

                <h4>
                    <span>Account</span>
                    <div class="menu-separator"></div>
                </h4>
                <li>
                    <a href="../../logout.php" onclick="confirmLogout(event)">
                        <span class="material-symbols-outlined">logout</span>Logout
                    </a>
                </li>
            </ul>
            <a href="../teacherprofile.php" class="user-account-link">
                <div class="user-account">
                    <div class="user-profile">
                        <img src="<?php echo '../../uploads/' . htmlspecialchars($_SESSION['user']['img']) . '?' . time(); ?>"
                            alt="Profile Image" />
                        <div class="user-detail">
                            <h3><?php echo htmlspecialchars($_SESSION['user']['nickname']); ?></h3>
                            <span>Teacher's Profile</span>
                        </div>
                    </div>
                </div>
            </a>
        </aside>

        <div class="col-md-9" id="mainContent">
            <div class="form-container">
                <h2 class="text-center mb-4">Manage Subject Collaborations</h2>
                
                <!-- Add Collaborator Form -->
                <form id="addCollaboratorForm">
                    <input type="hidden" name="action" value="add">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="subject_id">Select Subject:</label>
                                <select class="form-select" id="subject_id" name="subject_id" required>
                                    <option value="" disabled selected>Choose a subject...</option>
                                    <?php while($subject = $subjectsResult->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($subject['subject_id']); ?>">
                                            <?php echo htmlspecialchars($subject['subject_name']); ?> 
                                            (Year <?php echo htmlspecialchars($subject['year_level']); ?> - 
                                            Section <?php echo htmlspecialchars($subject['section']); ?> - 
                                            SY <?php echo htmlspecialchars($subject['school_year']); ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="teacher_id">Select Teacher:</label>
                                <select class="form-select" id="teacher_id" name="teacher_id" required>
                                    <option value="" disabled selected>Choose a teacher...</option>
                                    <?php while($teacher = $teachersResult->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($teacher['user_id']); ?>">
                                            <?php echo htmlspecialchars($teacher['full_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa-solid fa-user-plus"></i> Add Collaborator
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Current Collaborators List -->
                <div class="collaborators-list">
                    <h3>Current Collaborators</h3>
                    <table id="collaboratorsTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Year Level</th>
                                <th>Section</th>
                                <th>School Year</th>
                                <th>Collaborator</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($collab = $collabsResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($collab['subject_name']); ?></td>
                                    <td><?php echo htmlspecialchars($collab['year_level']); ?></td>
                                    <td><?php echo htmlspecialchars($collab['section']); ?></td>
                                    <td><?php echo htmlspecialchars($collab['school_year']); ?></td>
                                    <td><?php echo htmlspecialchars($collab['teacher_name']); ?></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm remove-collaborator" 
                                                data-id="<?php echo $collab['collaboration_id']; ?>">
                                            <i class="fa-solid fa-user-minus"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#collaboratorsTable').DataTable({
                "order": [[0, "asc"]]
            });

            // Handle Add Collaborator Form Submission
            $('#addCollaboratorForm').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: 'manage_collaboration.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred while processing your request.'
                        });
                    }
                });
            });

            // Handle Remove Collaborator
            $('.remove-collaborator').click(function() {
                const collaborationId = $(this).data('id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will remove the teacher's access to this subject.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'manage_collaboration.php',
                            type: 'POST',
                            data: {
                                action: 'remove',
                                collaboration_id: collaborationId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: response.message
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'An error occurred while processing your request.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
