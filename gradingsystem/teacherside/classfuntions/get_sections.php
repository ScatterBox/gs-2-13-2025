<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradingsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['year_level'])) {
    $year_level = $_GET['year_level'];
    
    $stmt = $conn->prepare("SELECT DISTINCT section FROM students WHERE year_level = ? ORDER BY section");
    $stmt->bind_param("s", $year_level);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sections = array();
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row['section'];
    }
    
    header('Content-Type: application/json');
    echo json_encode($sections);
    
    $stmt->close();
}

$conn->close();
?>
