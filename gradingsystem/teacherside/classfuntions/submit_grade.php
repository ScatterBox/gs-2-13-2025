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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $subject_id = isset($_POST['subject_id']) ? intval($_POST['subject_id']) : 0;
    $category = isset($_POST['category']) ? $_POST['category'] : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $score = isset($_POST['score']) ? intval($_POST['score']) : 0;
    $total_marks = isset($_POST['total_marks']) ? intval($_POST['total_marks']) : 0;
    $grading_period = isset($_POST['grading_period']) ? trim($_POST['grading_period']) : '';
    $teacher_id = $_SESSION['user_id'];
    $date = date('Y-m-d');

    if ($student_id == 0 || $subject_id == 0 || empty($category) || empty($name) || empty($grading_period) || $score < 0 || $total_marks <= 0) {
        echo "<script>
                alert('Invalid input! Please ensure all fields are filled correctly.');
                window.history.back();
              </script>";
        exit();
    }

    $tables = [
        'performance_tasks' => 'performance_tasks',
        'quarterly_assessment' => 'quarterly_assessment',
        'written_works' => 'written_works'
    ];
    
    if (!isset($tables[$category])) {
        echo "<script>
                alert('Invalid category selected!');
                window.history.back();
              </script>";
        exit();
    }
    
    $table_name = $tables[$category];

    $sql = "INSERT INTO $table_name (student_id, subject_id, name, total_score, total_marks, grading_period, date) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisiiss", $student_id, $subject_id, $name, $score, $total_marks, $grading_period, $date);

    if ($stmt->execute()) {
        echo "<script>
                alert('Grade submitted successfully!');
                window.location.href = 'mystudents.php';
              </script>";
    } else {
        echo "<script>
                alert('Error submitting grade: " . $stmt->error . "');
                window.history.back();
              </script>";
    }
    $stmt->close();
}
$conn->close();
?>
