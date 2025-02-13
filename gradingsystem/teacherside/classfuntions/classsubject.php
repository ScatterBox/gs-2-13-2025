<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../login.php");
    exit();
}

// Fetch user details
$userImg = !empty($_SESSION['user']['img']) ? '../uploads/' . htmlspecialchars($_SESSION['user']['img']) : '../images/default-profile.jpg';
$userNickname = htmlspecialchars($_SESSION['user']['nickname'] ?? 'Admin');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradingsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$year_level = $_GET['year_level'];
$section = $_GET['section'];
$subject = $_GET['subject'];

// Fetch students in the specified year level and section with the assigned subject
$sql = "SELECT s.user_id, s.fname, s.mname, s.lname, s.nickname, s.age, s.sex, s.birthdate, s.address, s.email, s.ename
        FROM students s
        JOIN student_subjects ss ON s.user_id = ss.student_id
        JOIN subjects sub ON ss.subject_id = sub.subject_id
        WHERE sub.subject_name = ? AND s.year_level = ? AND s.section = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $subject, $year_level, $section);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Class Subject - Students List</title>

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

    <!-- Include SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Include Material Icons CSS -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <script src="../../scripts/confirmlogout.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../styles/style1.css">
    <link rel="stylesheet" href="../ttablestyles/style.css">
    <!-- Favicon -->
    <link rel="icon" href="logo.jpg">
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
                    <a href="../../logout.php" onclick="return confirmLogout()">
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

        <!-- Main content -->
        <div class="col-md-9" id="mainContent">
            <div class="container">
                <h1>Students List for <?= htmlspecialchars($subject) ?> - <?= htmlspecialchars($year_level) ?> -
                    <?= htmlspecialchars($section) ?>
                </h1>
                <table id="studentsTable" class="table table-striped smaller-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Performance Tasks</th>
                            <th>Quarterly Assessments</th>
                            <th>Written Works</th>
                            <th>Final Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['user_id']) ?></td>
                                <td><?= htmlspecialchars($student['fname'] . ' ' . $student['mname'] . ' ' . $student['lname'] . ' ' . ($student['ename'] ?? '')) ?>
                                </td>
                                <td><a class="btn btn-info"
                                        href="performance_tasks.php?student_id=<?= $student['user_id'] ?>">View</a></td>
                                <td><a class="btn btn-info"
                                        href="quarterly_assessments.php?student_id=<?= $student['user_id'] ?>">View</a></td>
                                <td><a class="btn btn-info"
                                        href="written_works.php?student_id=<?= $student['user_id'] ?>">View</a></td>
                                <td></td> <!-- Final Grade column left blank -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#studentsTable').DataTable({
                "scrollX": true // Ensure this is inside the initialization object
            });
        });
    </script>

</body>

</html>