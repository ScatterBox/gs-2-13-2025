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

if (!isset($_POST['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Admin ID is required']);
    exit();
}

$admin_id = $_POST['admin_id'];

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

// Prevent deleting the last admin
$count_sql = "SELECT COUNT(*) as admin_count FROM admins";
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();

if ($count_row['admin_count'] <= 1) {
    echo json_encode(['success' => false, 'message' => 'Cannot delete the last admin account']);
    $conn->close();
    exit();
}

// Delete the admin
$delete_sql = "DELETE FROM admins WHERE user_id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("i", $admin_id);

if ($delete_stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Admin deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete admin']);
}

$delete_stmt->close();
$conn->close();
?>
