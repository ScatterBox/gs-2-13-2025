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

// Validate required fields
$required_fields = ['teacher_id', 'fname', 'mname', 'lname', 'age', 'sex', 'address'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst($field) . ' is required']);
        exit();
    }
}

// Sanitize and get input values
$teacher_id = $_POST['teacher_id'];
$fname = $_POST['fname'];
$mname = $_POST['mname'];
$lname = $_POST['lname'];
$ename = $_POST['ename'] ?? '';
$age = $_POST['age'];
$sex = $_POST['sex'];
$address = $_POST['address'];

// Check if teacher exists
$check_sql = "SELECT user_id FROM teachers WHERE user_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $teacher_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Teacher not found']);
    $check_stmt->close();
    $conn->close();
    exit();
}
$check_stmt->close();

// Update teacher information
$sql = "UPDATE teachers 
        SET fname = ?, mname = ?, lname = ?, ename = ?, 
            age = ?, sex = ?, address = ? 
        WHERE user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssi", 
    $fname, $mname, $lname, $ename, 
    $age, $sex, $address, $teacher_id
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Teacher information updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update teacher information']);
}

$stmt->close();
$conn->close();
?>