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
$required_fields = ['admin_id', 'fname', 'mname', 'lname', 'age', 'birthdate', 'sex', 'email', 'username', 'address'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst($field) . ' is required']);
        exit();
    }
}

// Sanitize and get input values
$admin_id = $_POST['admin_id'];
$fname = $_POST['fname'];
$mname = $_POST['mname'];
$lname = $_POST['lname'];
$ename = $_POST['ename'] ?? '';
$age = $_POST['age'];
$birthdate = $_POST['birthdate'];
$sex = $_POST['sex'];
$email = $_POST['email'];
$username = $_POST['username'];
$address = $_POST['address'];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit();
}

// Check if admin exists
$check_sql = "SELECT user_id FROM admins WHERE user_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $admin_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Admin not found']);
    $check_stmt->close();
    $conn->close();
    exit();
}

// Check if username is already taken by another admin
$username_check_sql = "SELECT user_id FROM admins WHERE username = ? AND user_id != ?";
$username_check_stmt = $conn->prepare($username_check_sql);
$username_check_stmt->bind_param("si", $username, $admin_id);
$username_check_stmt->execute();
$username_check_result = $username_check_stmt->get_result();

if ($username_check_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username is already taken']);
    $username_check_stmt->close();
    $conn->close();
    exit();
}

// Update admin information
$update_sql = "UPDATE admins SET 
               fname = ?, 
               mname = ?, 
               lname = ?, 
               ename = ?, 
               age = ?, 
               birthdate = ?, 
               sex = ?, 
               email = ?, 
               username = ?, 
               address = ? 
               WHERE user_id = ?";

$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("ssssisssssi", 
    $fname, 
    $mname, 
    $lname, 
    $ename, 
    $age, 
    $birthdate, 
    $sex, 
    $email, 
    $username, 
    $address, 
    $admin_id
);

if ($update_stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Admin information updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update admin information']);
}

$update_stmt->close();
$conn->close();
?>
