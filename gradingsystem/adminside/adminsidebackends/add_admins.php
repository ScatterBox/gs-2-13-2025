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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $fname = ucfirst($_POST['fname']);
    $mname = ucfirst($_POST['mname']);
    $lname = ucfirst($_POST['lname']);
    $ename = $_POST['ename'] === 'None' ? '' : ucfirst($_POST['ename']);
    $nickname = ucfirst($_POST['nickname']);
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $birthdate = $_POST['birthdate'];
    $address = ucfirst($_POST['address']);
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if a user with the same name already exists
    $checkNameSql = "SELECT 1 FROM admins WHERE fname = ? AND mname = ? AND lname = ? AND ename = ?";
    $stmt = $conn->prepare($checkNameSql);
    $stmt->bind_param("ssss", $fname, $mname, $lname, $ename);
    $stmt->execute();
    $resultName = $stmt->get_result();

    if ($resultName->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'A user with the same name already exists']);
        exit();
    }

    // Check if a user with the same username already exists
    $checkUsernameSql = "SELECT 1 FROM admins WHERE username = ?";
    $stmt = $conn->prepare($checkUsernameSql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $resultUsername = $stmt->get_result();

    if ($resultUsername->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'A user with the same username already exists']);
        exit();
    }

    // Insert data into the admins table
    $sql = "INSERT INTO admins (fname, mname, lname, ename, nickname, age, sex, birthdate, address, username, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $fname, $mname, $lname, $ename, $nickname, $age, $sex, $birthdate, $address, $username, $password);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Admin account created successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>