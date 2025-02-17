<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradingsystem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

if (!isset($_GET['admin_id'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Admin ID is required']);
    exit();
}

$admin_id = $_GET['admin_id'];

$sql = "SELECT user_id, fname, mname, lname, ename, age, birthdate, sex, email, username, address 
        FROM admins 
        WHERE user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'Admin not found']);
    exit();
}

$admin = $result->fetch_assoc();
echo json_encode($admin);

$stmt->close();
$conn->close();
?>
