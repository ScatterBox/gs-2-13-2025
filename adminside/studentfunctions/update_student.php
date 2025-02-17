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

// Debug: Log POST data
error_log("POST data: " . print_r($_POST, true));

// Validate required fields
$required_fields = ['student_id', 'fname', 'mname', 'lname', 'age', 'sex', 'birthdate', 'address', 'year_level', 'section', 'email', 'lrn'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst($field) . ' is required']);
        exit();
    }
}

// Sanitize and get input values
$student_id = intval($_POST['student_id']);
$fname = trim($_POST['fname']);
$mname = trim($_POST['mname']);
$lname = trim($_POST['lname']);
$ename = isset($_POST['ename']) ? trim($_POST['ename']) : '';
$age = intval($_POST['age']);
$sex = trim($_POST['sex']);
$birthdate = trim($_POST['birthdate']);
$address = trim($_POST['address']);
$year_level = trim($_POST['year_level']);
$section = trim($_POST['section']);
$email = trim($_POST['email']);
$lrn = trim($_POST['lrn']);

// Debug: Log processed variables
error_log("Processed data - Address: " . $address);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit();
}

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

// Check if LRN is already taken by another student
$lrn_check_sql = "SELECT user_id FROM students WHERE lrn = ? AND user_id != ?";
$lrn_check_stmt = $conn->prepare($lrn_check_sql);
$lrn_check_stmt->bind_param("si", $lrn, $student_id);
$lrn_check_stmt->execute();
$lrn_check_result = $lrn_check_stmt->get_result();

if ($lrn_check_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'LRN is already taken by another student']);
    $lrn_check_stmt->close();
    $conn->close();
    exit();
}

// Update student information
$update_sql = "UPDATE students SET 
               fname = ?, 
               mname = ?, 
               lname = ?, 
               ename = ?, 
               age = ?, 
               sex = ?, 
               birthdate = ?, 
               address = ?, 
               year_level = ?, 
               section = ?, 
               email = ?, 
               lrn = ? 
               WHERE user_id = ?";

$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("ssssisssssssi", 
    $fname, 
    $mname, 
    $lname, 
    $ename, 
    $age, 
    $sex, 
    $birthdate, 
    $address,  
    $year_level, 
    $section, 
    $email, 
    $lrn, 
    $student_id
);

// Debug: Log SQL and parameters
error_log("Update SQL: " . $update_sql);
error_log("Parameters: " . print_r([
    $fname, $mname, $lname, $ename, $age, $sex, $birthdate, 
    $address, $year_level, $section, $email, $lrn, $student_id
], true));

if ($update_stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Student updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update student: ' . $update_stmt->error]);
}

$update_stmt->close();
$conn->close();
?>
