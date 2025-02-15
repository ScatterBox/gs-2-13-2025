<?php
session_start();
if ($_SESSION['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

// Fetch user details
$userImg = !empty($_SESSION['user']['img']) ? '../uploads/' . htmlspecialchars($_SESSION['user']['img']) : '../images/default-profile.jpg';
$userNickname = htmlspecialchars($_SESSION['user']['nickname'] ?? 'Admin');


$host = 'localhost';
$username = 'root';
$password = '';
$database = 'gradingsystem';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student details from URL parameters
$user_id = isset($_GET['user_id']) ? htmlspecialchars($_GET['user_id']) : '';
$fname = isset($_GET['fname']) ? htmlspecialchars($_GET['fname']) : '';
$mname = isset($_GET['mname']) ? htmlspecialchars($_GET['mname']) : '';
$lname = isset($_GET['lname']) ? htmlspecialchars($_GET['lname']) : '';
$ename = isset($_GET['ename']) ? htmlspecialchars($_GET['ename']) : ''; // Optional
$section = isset($_GET['section']) ? htmlspecialchars($_GET['section']) : 'Unknown Section';
$year_level = isset($_GET['year_level']) ? htmlspecialchars($_GET['year_level']) : 'Unknown Year Level';

// Build full name
$fullname = trim("$fname $mname $lname $ename");

// Fetch subjects assigned to the student
$subjects = [];
if (!empty($user_id)) {
    $sql = "SELECT sb.subject_id, sb.subject_name 
            FROM student_subjects ss
            INNER JOIN subjects sb ON ss.subject_id = sb.subject_id
            WHERE ss.student_id = ? AND (sb.created_by = ? OR EXISTS (
                SELECT 1 FROM teacher_collaborations tc 
                WHERE tc.subject_id = sb.subject_id AND tc.teacher_id = ?
            ))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $_SESSION['user']['user_id'], $_SESSION['user']['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
    $stmt->close();
}
$conn->close();
?>

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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


<!-- Include Material Icons CSS -->
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<script src="../../scripts/confirmlogout.js"></script>


<link rel="stylesheet" href="../../styles/style1.css">
<link rel="stylesheet" href="../ttablestyles/style.css">
<!-- Favicon -->
<link rel="icon" href="logo.jpg">


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

        <!-- Main content -->
        <div class="col-md-9" id="mainContent">
            <h1>Name: <?php echo $fullname; ?></h1>
            <h4>Class: <?php echo strtoupper($year_level) . ' - ' . strtoupper($section); ?></h4>
            <!-- ✅ Fixed Format -->

            <form action="submit_grade.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                <input type="hidden" name="fullname" value="<?php echo $fullname; ?>">
                <input type="hidden" name="year_level" value="<?php echo $year_level; ?>">
                <input type="hidden" name="section" value="<?php echo $section; ?>">

                <!-- Choose Subject Dropdown -->
                <div class="mb-3">
                    <label for="subject" class="form-label">Choose Subject</label>
                    <select name="subject_id" id="subject" class="form-control" required>
                        <option value="" disabled selected>Select Subject</option>
                        <?php if (!empty($subjects)): ?>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo $subject['subject_id']; ?>">
                                    <?php echo htmlspecialchars($subject['subject_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No subjects assigned</option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Choose Category Dropdown -->
                <div class="mb-3">
                    <label for="category" class="form-label">Choose Category</label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="" disabled selected>Select Category</option>
                        <option value="performance_tasks">Performance Task</option>
                        <option value="quarterly_assessment">Quarterly Assessment</option>
                        <option value="written_works">Written Works</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="grading_period" class="form-label">Choose Grading Period</label>
                    <select name="grading_period" id="grading_period" class="form-control" required>
                        <option value="" disabled selected>Select Grading Period</option>
                        <option value="First">First</option>
                        <option value="Second">Second</option>
                        <option value="Third">Third</option>
                        <option value="Fourth">Fourth</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Graded Activity Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <!-- Score Input -->
                <div class="mb-3">
                    <label for="score" class="form-label">Score</label>
                    <input type="number" name="score" id="score" class="form-control" min="0" max="100" required>
                </div>

                <!-- Total Marks Input -->
                <div class="mb-3">
                    <label for="total_marks" class="form-label">Total Marks</label>
                    <input type="number" name="total_marks" id="total_marks" class="form-control" min="1" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">Submit Grade</button>
            </form>
        </div>
    </div>


    <script src="../scripts/checkSession.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>