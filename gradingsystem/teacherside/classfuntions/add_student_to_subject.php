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

// Fetch all students
$studentsSql = "SELECT user_id, CONCAT(fname, ' ', mname, ' ', lname, ' ', COALESCE(ename, '')) as full_name, 
                year_level, section FROM students ORDER BY year_level, section, fname";
$studentsResult = $conn->query($studentsSql);

// Fetch subjects taught by the logged-in teacher
$subjectsSql = "SELECT subject_id, subject_name, year_level, section, school_year 
                FROM subjects WHERE created_by = ? ORDER BY year_level, section, subject_name";
$stmt = $conn->prepare($subjectsSql);
$stmt->bind_param("i", $loggedInUserId);
$stmt->execute();
$subjectsResult = $stmt->get_result();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    
    // Check if student is already enrolled in this subject
    $checkSql = "SELECT * FROM student_subjects WHERE student_id = ? AND subject_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $student_id, $subject_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $response = ["success" => false, "message" => "Student is already enrolled in this subject"];
    } else {
        // Add student to subject
        $insertSql = "INSERT INTO student_subjects (student_id, subject_id) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ii", $student_id, $subject_id);
        
        if ($insertStmt->execute()) {
            $response = ["success" => true, "message" => "Student successfully added to the subject"];
        } else {
            $response = ["success" => false, "message" => "Error adding student to subject: " . $conn->error];
        }
        $insertStmt->close();
    }
    $checkStmt->close();
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student to Subject</title>
    
    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

    <!-- Include Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

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
        .select2-container {
            width: 100% !important;
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
                <h2 class="text-center mb-4">Add Student to Subject</h2>
                <form id="addStudentForm">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Select Student:</label>
                        <select class="form-select" id="student_id" name="student_id" required>
                            <option value="" disabled selected>Choose a student...</option>
                            <?php while($student = $studentsResult->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($student['user_id']); ?>">
                                    <?php echo htmlspecialchars($student['full_name']); ?> 
                                    (Year <?php echo htmlspecialchars($student['year_level']); ?> - 
                                    Section <?php echo htmlspecialchars($student['section']); ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Select Subject:</label>
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

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Add Student to Subject</button>
                        <a href="mystudents.php" class="btn btn-secondary">Back to My Students</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#addStudentForm').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: 'add_student_to_subject.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'mystudents.php';
                                }
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
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
