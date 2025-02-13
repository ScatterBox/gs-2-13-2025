<?php
include('../../conn.php'); // Include your database connection file
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// If the check passes, the user is an admin and logged in
// Continue with your admin page content below...
// Fetch the faculty data from the database
$sql = "SELECT t.user_id, t.fname, t.mname, t.lname, t.ename, t.age, t.sex, t.address, 
               GROUP_CONCAT(s.subject_name SEPARATOR ', ') AS subjects 
        FROM teachers t
        LEFT JOIN subjects s ON t.user_id = s.created_by
        GROUP BY t.user_id";

$result = $conn->query($sql);

$teachers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}

// Fetch user details
$userImg = !empty($_SESSION['user']['img']) ? '../uploads/' . htmlspecialchars($_SESSION['user']['img']) : '../images/default-profile.jpg';
$userNickname = htmlspecialchars($_SESSION['user']['nickname'] ?? 'Admin');

?>