<?php
session_start();
if ($_SESSION['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

// Fetch user details
$userImg = !empty($_SESSION['user']['img']) ? '../uploads/' . htmlspecialchars($_SESSION['user']['img']) : '../images/default-profile.jpg';
$userNickname = htmlspecialchars($_SESSION['user']['nickname'] ?? 'Admin');


$loggedInUserId = $_SESSION['user_id']; // Assuming user_id is stored in session

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'gradingsystem';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student data with subjects assigned by the logged-in teacher or where teacher is a collaborator
$sql = "SELECT s.user_id, s.fname, s.mname, s.lname, s.ename, s.age, s.sex, s.address, 
               s.year_level, s.section, s.email, 
               GROUP_CONCAT(DISTINCT sb.subject_name SEPARATOR ', ') AS subjects,
               GROUP_CONCAT(DISTINCT 
                    CASE 
                        WHEN sb.created_by = ? THEN 'Owner'
                        ELSE 'Collaborator'
                    END 
                    SEPARATOR ', ') as roles
        FROM students s
        INNER JOIN student_subjects ss ON s.user_id = ss.student_id
        INNER JOIN subjects sb ON ss.subject_id = sb.subject_id 
        WHERE sb.created_by = ? 
        OR sb.subject_id IN (
            SELECT subject_id 
            FROM teacher_collaborations 
            WHERE teacher_id = ?
        )
        GROUP BY s.user_id, s.fname, s.mname, s.lname, s.ename, s.age, s.sex, 
                 s.address, s.year_level, s.section, s.email";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $loggedInUserId, $loggedInUserId, $loggedInUserId);
$stmt->execute();
$result = $stmt->get_result();
$students = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            'user_id' => isset($row['user_id']) ? $row['user_id'] : '',
            'fname' => isset($row['fname']) ? $row['fname'] : '',
            'mname' => isset($row['mname']) ? $row['mname'] : '',
            'lname' => isset($row['lname']) ? $row['lname'] : '',
            'ename' => isset($row['ename']) ? $row['ename'] : '',  // Optional
            'fullname' => trim("{$row['fname']} {$row['mname']} {$row['lname']} {$row['ename']}"),
            'age' => isset($row['age']) ? $row['age'] : 'N/A',
            'sex' => isset($row['sex']) ? $row['sex'] : 'N/A',
            'address' => isset($row['address']) ? $row['address'] : 'N/A',
            'year_level' => isset($row['year_level']) ? $row['year_level'] : 'N/A',
            'section' => isset($row['section']) ? $row['section'] : 'N/A',
            'subjects' => !empty($row['subjects']) ? $row['subjects'] : 'No subjects',
            'roles' => !empty($row['roles']) ? $row['roles'] : 'No roles',
            'email' => isset($row['email']) ? $row['email'] : 'N/A'
        ];
    }
}
$stmt->close();
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
            <h1 style="text-align: center;">Student with my subjects</h1>
            <div class="mb-3 text-end">
                <a href="add_student_to_subject.php" class="btn btn-primary">
                    <i class="fa-solid fa-user-plus"></i> Add Student to Subject
                </a>
            </div>
            <table id="studentTable" class="table table-striped smaller-table" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Address</th>
                        <th>Year Level</th>
                        <th>Section</th>
                        <th>Subjects</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $index => $student): ?>
                        <tr>
                            <th scope="row"><?php echo $index + 1; ?></th>
                            <td><?php echo htmlspecialchars($student['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($student['age']); ?></td>
                            <td><?php echo htmlspecialchars($student['sex']); ?></td>
                            <td><?php echo htmlspecialchars($student['address']); ?></td>
                            <td><?php echo htmlspecialchars($student['year_level']); ?></td>
                            <td><?php echo htmlspecialchars($student['section']); ?></td>
                            <td><?php echo htmlspecialchars($student['subjects']); ?></td>
                            <td><?php echo htmlspecialchars($student['roles']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td>
                                <a class="btn btn-info view-btn" href="gradestudent.php?user_id=<?php echo urlencode($student['user_id']); ?>
                                    &fname=<?php echo urlencode($student['fname']); ?>
                                    &mname=<?php echo urlencode($student['mname']); ?>
                                    &lname=<?php echo urlencode($student['lname']); ?>
                                    &ename=<?php echo urlencode($student['ename']); ?>
                                    &year_level=<?php echo urlencode($student['year_level']); ?>
                                    &section=<?php echo urlencode($student['section']); ?>">
                                    Grade
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#studentTable').DataTable({
                "scrollX": true
            });
        });
    </script>

    <script src="../scripts/checkSession.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>