<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
// If the check passes, the user is an admin and logged in
// Continue with your admin page content below...
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'gradingsystem';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST["fname"];
    $mname = $_POST["mname"];
    $lname = $_POST["lname"];
    $ename = $_POST["ename"];
    $nickname = $_POST["nickname"];
    $age = $_POST["age"];
    $sex = $_POST["sex"];
    $birthdate = $_POST["birthdate"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    $checkSql = "SELECT * FROM teachers WHERE username='$username'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Error: A user with the same username already exists.']);
    } else {
        $checkSql = "SELECT * FROM teachers WHERE fname='$fname' AND mname='$mname' AND lname='$lname'";
        if ($ename) {
            $checkSql .= " AND ename='$ename'";
        }
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Error: A user with the same full name already exists.']);
        } else {
            $sql = "INSERT INTO teachers (fname, mname, lname, ename, nickname, age, sex, birthdate, address, email, username, password)
                    VALUES ('$fname', '$mname', '$lname', '$ename', '$nickname', '$age', '$sex', '$birthdate', '$address', '$email', '$username', '$password')";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(['success' => true, 'message' => 'New record created successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
            }
        }
    }
    $conn->close();
    exit;
}

// Fetch user details
$userImg = !empty($_SESSION['user']['img']) ? '../uploads/' . htmlspecialchars($_SESSION['user']['img']) : '../images/default-profile.jpg';
$userNickname = htmlspecialchars($_SESSION['user']['nickname'] ?? 'Admin');

?>