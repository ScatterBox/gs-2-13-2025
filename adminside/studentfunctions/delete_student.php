<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradingsystem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

if (!isset($_POST['student_id'])) {
    echo json_encode(['success' => false, 'message' => 'Student ID is required']);
    exit();
}

$student_id = $_POST['student_id'];

// Check if student exists
$check_sql = "SELECT user_id FROM students WHERE user_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $student_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Student not found']);
    $check_stmt->close();
    $conn->close();
    exit();
}

// First delete related records from student_subjects
$delete_subjects_sql = "DELETE FROM student_subjects WHERE student_id = ?";
$delete_subjects_stmt = $conn->prepare($delete_subjects_sql);
$delete_subjects_stmt->bind_param("i", $student_id);
$delete_subjects_stmt->execute();

// Then delete the student
$delete_sql = "DELETE FROM students WHERE user_id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("i", $student_id);

if ($delete_stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Student deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete student']);
}

$delete_stmt->close();
$conn->close();
?>
