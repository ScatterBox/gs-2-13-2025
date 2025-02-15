<?php 
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradingsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student details
$student_id = $_GET['student_id'] ?? '';
$subject_id = $_GET['subject_id'] ?? '';

if (empty($subject_id)) {
    die("Error: Missing subject_id");
}

// Get all students in this subject
$sql = "SELECT s.user_id, CONCAT(s.fname, ' ', s.mname, ' ', s.lname, ' ', COALESCE(s.ename, '')) as full_name
        FROM students s
        JOIN student_subjects ss ON s.user_id = ss.student_id
        WHERE ss.subject_id = ?
        ORDER BY s.lname, s.fname";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$all_students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// If no student_id is provided, use the first student
if (empty($student_id) && !empty($all_students)) {
    $student_id = $all_students[0]['user_id'];
}

// Get student and subject details
$sql = "SELECT s.*, 
               CONCAT(s.fname, ' ', s.mname, ' ', s.lname, ' ', COALESCE(s.ename, '')) AS full_name,
               sub.subject_name,
               sub.year_level,
               sub.section
        FROM students s
        JOIN student_subjects ss ON s.user_id = ss.student_id
        JOIN subjects sub ON ss.subject_id = sub.subject_id
        WHERE s.user_id = ? AND sub.subject_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $subject_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Student or subject not found");
}

$student = $result->fetch_assoc();

$records = [];
$totalScore = 0;
$totalMarks = 0;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['category'])) {
    $category = $_GET['category'];
    $grading_period = $_GET['grading_period'] ?? null;

    $tables = [
        'written_works' => 'written_works',
        'performance_tasks' => 'performance_tasks',
        'quarterly_assessments' => 'quarterly_assessment'
    ];
    
    if (!isset($tables[$category])) {
        die("Invalid category");
    }
    
    $table = $tables[$category];
    
    $sql = "SELECT t.*, s.subject_name 
            FROM $table t 
            JOIN subjects s ON t.subject_id = s.subject_id
            WHERE t.subject_id = ? AND t.student_id = ?";
    
    $stmt = $conn->prepare($sql);
    if ($grading_period) {
        $stmt->bind_param("iis", $subject_id, $student_id, $grading_period);
    } else {
        $stmt->bind_param("ii", $subject_id, $student_id);
    }
    
    $stmt->execute();
    $records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    foreach ($records as $record) {
        $totalScore += $record['total_score'];
        $totalMarks += $record['total_marks'];
    }
}

// Function to get available grading periods
function getAvailableGradingPeriods($conn, $category, $subject_id, $student_id) {
    $tables = [
        'written_works' => 'written_works',
        'performance_tasks' => 'performance_tasks',
        'quarterly_assessments' => 'quarterly_assessment'
    ];
    
    if (!isset($tables[$category])) {
        return [];
    }
    
    $table = $tables[$category];
    $sql = "SELECT DISTINCT grading_period FROM $table t 
            WHERE t.subject_id = ? AND t.student_id = ? ORDER BY grading_period";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $subject_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $periods = [];
    while ($row = $result->fetch_assoc()) {
        $periods[] = $row['grading_period'];
    }
    return $periods;
}

// Ensure getAvailableGradingPeriods is called correctly
if (isset($_GET['category']) && !empty($student_id)) {
    $availablePeriods = getAvailableGradingPeriods($conn, $_GET['category'], $subject_id, $student_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Performance</title>
    
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../styles/style1.css">
    
    <style>
        .student-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .filters-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .student-selection {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .student-selection select {
            font-size: 1.1em;
            padding: 10px;
        }
        .student-selection .btn {
            padding: 10px 20px;
        }
        .alert-info {
            background-color: #e3f2fd;
            border-color: #90caf9;
            color: #0d47a1;
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
            <div class="container">
                <!-- Student Selection -->
                <div class="student-selection mb-4">
                    <form id="studentForm" method="GET" class="row g-3 align-items-end">
                        <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subject_id); ?>">
                        <?php if (isset($_GET['category'])): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
                        <?php endif; ?>
                        <?php if (isset($_GET['grading_period'])): ?>
                            <input type="hidden" name="grading_period" value="<?php echo htmlspecialchars($_GET['grading_period']); ?>">
                        <?php endif; ?>
                        
                        <div class="col-md-9">
                            <label for="student_id" class="form-label fw-bold">Select Student:</label>
                            <select class="form-select form-select-lg" id="student_id" name="student_id">
                                <?php foreach ($all_students as $s): ?>
                                    <option value="<?php echo htmlspecialchars($s['user_id']); ?>" 
                                            <?php echo ($s['user_id'] == $student_id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($s['full_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-sync"></i> Change Student
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Student Information -->
                <div class="student-info">
                    <h2>Student Performance Record</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> <?php echo $student ? htmlspecialchars($student['full_name']) : 'N/A'; ?></p>
                            <p><strong>Subject:</strong> <?php echo $student ? htmlspecialchars($student['subject_name']) : 'N/A'; ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Year Level:</strong> <?php echo $student ? htmlspecialchars($student['year_level']) : 'N/A'; ?></p>
                            <p><strong>Section:</strong> <?php echo $student ? htmlspecialchars($student['section']) : 'N/A'; ?></p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <a href="gradestudent.php?user_id=<?php echo urlencode($student_id); ?>&fname=<?php echo urlencode($student['fname']); ?>&mname=<?php echo urlencode($student['mname']); ?>&lname=<?php echo urlencode($student['lname']); ?>&ename=<?php echo urlencode($student['ename']); ?>&year_level=<?php echo urlencode($student['year_level']); ?>&section=<?php echo urlencode($student['section']); ?>" 
                               class="btn btn-primary">
                                <i class="fas fa-plus-circle"></i> Add Grade
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filters-section">
                    <form id="filterForm" method="GET" class="row g-3">
                        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
                        <input type="hidden" name="subject_id" value="<?php echo htmlspecialchars($subject_id); ?>">
                        
                        <div class="col-md-6">
                            <label for="category" class="form-label">Category:</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="" disabled <?php echo !isset($_GET['category']) ? 'selected' : ''; ?>>Select Category</option>
                                <option value="written_works" <?php echo (isset($_GET['category']) && $_GET['category'] === 'written_works') ? 'selected' : ''; ?>>Written Works</option>
                                <option value="performance_tasks" <?php echo (isset($_GET['category']) && $_GET['category'] === 'performance_tasks') ? 'selected' : ''; ?>>Performance Tasks</option>
                                <option value="quarterly_assessments" <?php echo (isset($_GET['category']) && $_GET['category'] === 'quarterly_assessments') ? 'selected' : ''; ?>>Quarterly Assessments</option>
                            </select>
                        </div>

                        <?php if (isset($_GET['category'])): ?>
                        <?php $availablePeriods = getAvailableGradingPeriods($conn, $_GET['category'], $subject_id, $student_id); ?>
                        <div class="col-md-6">
                            <label for="grading_period" class="form-label">Grading Period:</label>
                            <select class="form-select" id="grading_period" name="grading_period">
                                <option value="">All Periods</option>
                                <?php foreach ($availablePeriods as $period): ?>
                                    <option value="<?php echo htmlspecialchars($period); ?>" 
                                            <?php echo (isset($_GET['grading_period']) && $_GET['grading_period'] === $period) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($period); ?> Grading
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Apply Filters
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Performance Records Table -->
                <?php if (isset($records) && !empty($records)): ?>
                    <div class="table-responsive">
                        <div class="alert alert-info">
                            <strong>Summary:</strong> 
                            Total Score: <?php echo number_format($totalScore, 2); ?> / <?php echo number_format($totalMarks, 2); ?>
                        </div>
                        <table id="performanceTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Score</th>
                                    <th>Total Marks</th>
                                    <th>Grading Period</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($records as $record): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($record['name']); ?></td>
                                        <td><?php echo htmlspecialchars($record['total_score']); ?></td>
                                        <td><?php echo htmlspecialchars($record['total_marks']); ?></td>
                                        <td><?php echo htmlspecialchars($record['grading_period']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($record['date'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php elseif (isset($_GET['category'])): ?>
                    <div class="alert alert-info">
                        No records found for the selected category<?php echo isset($_GET['grading_period']) ? ' and grading period' : ''; ?>.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            if ($('#performanceTable').length) {
                $('#performanceTable').DataTable({
                    "order": [[4, "desc"]] // Sort by date by default
                });
            }

            // Auto-submit form when selections change
            $('#category, #grading_period').change(function() {
                if ($('#category').val() && ($('#grading_period').val() || $('#grading_period').val() === '')) {
                    $('#filterForm').submit();
                }
            });

            // Auto-submit when student changes
            $('#student_id').change(function() {
                $('#studentForm').submit();
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>