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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST["fname"];
    $mname = $_POST["mname"];
    $lname = $_POST["lname"];
    $ename = $_POST["ename"];
    $pname = $_POST["pname"];
    $lrn = $_POST["lrn"];
    $nickname = $_POST["nickname"];
    $age = $_POST["age"];
    $sex = $_POST["sex"];
    $birthdate = $_POST["birthdate"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $section = $_POST["section"];
    $year_level = $_POST["year_level"];

    // Check if a record with the same username already exists
    $checkSql = "SELECT * FROM students WHERE username='$username'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        echo "Error: A user with the same username already exists.";
    } else {
        // Check if a record with the same fname, mname, lname, and ename already exists
        $checkSql = "SELECT * FROM students WHERE fname='$fname' AND mname='$mname' AND lname='$lname'";
        if ($ename) {
            $checkSql .= " AND ename='$ename'";
        }
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            echo "Error: A user with the same full name already exists.";
        } else {
            // No such record exists, you can proceed with the INSERT operation
            $sql = "INSERT INTO students (fname, mname, lname, ename, pname, email, lrn, nickname, age, sex, birthdate, address, username, password, section, year_level)
            VALUES ('$fname', '$mname', '$lname', '$ename', '$pname', '$email', '$lrn', '$nickname', '$age', '$sex', '$birthdate', '$address', '$username', '$password', '$section', '$year_level')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>
                    Swal.fire(
                        'Success!',
                        'New record created successfully',
                        'success'
                    )
                </script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    $conn->close();
    exit();
}
?>
