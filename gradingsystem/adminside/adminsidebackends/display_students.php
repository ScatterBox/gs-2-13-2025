<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch user details
$userImg = !empty($_SESSION['user']['img']) ? '../uploads/' . htmlspecialchars($_SESSION['user']['img']) : '../images/default-profile.jpg';
$userNickname = htmlspecialchars($_SESSION['user']['nickname'] ?? 'Admin');


$host = 'localhost';
$username = 'root';
$password = '';
$database = 'gradingsystem';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student data from the database
$sql = "SELECT s.user_id, s.fname, s.mname, s.lname, s.ename, s.age, s.sex, s.birthdate, s.address, 
               s.year_level, s.section, s.email, s.lrn, 
               GROUP_CONCAT(sb.subject_name SEPARATOR ', ') AS subjects 
        FROM students s
        LEFT JOIN student_subjects ss ON s.user_id = ss.student_id
        LEFT JOIN subjects sb ON ss.subject_id = sb.subject_id AND sb.year_level = s.year_level AND sb.section = s.section
        GROUP BY s.user_id";

$result = $conn->query($sql);
$students = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            'fullname' => $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'] . ' ' . $row['ename'],
            'age' => $row['age'],
            'sex' => $row['sex'],
            'birthdate' => $row['birthdate'],
            'address' => $row['address'],
            'year_level' => $row['year_level'],
            'section' => $row['section'],
            'subjects' => $row['subjects'] ?: 'No subjects',
            'email' => $row['email'],
            'lrn' => $row['lrn']
        ];
    }
}
$conn->close();
?>