<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once("../../conn.php"); // Ensure database connection is included

// Fetch user details
$userImg = !empty($_SESSION['user']['img']) ? '../uploads/' . htmlspecialchars($_SESSION['user']['img']) : '../images/default-profile.jpg';
$userNickname = htmlspecialchars($_SESSION['user']['nickname'] ?? 'Admin');


// Fetch admin data from the `admins` table
$sql = "SELECT user_id, fname, mname, lname, ename, age, birthdate, sex, email, username, address
        FROM admins"; // Now fetching from `admins` table
$result = $conn->query($sql);
?>