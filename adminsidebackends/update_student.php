<?php
// ...existing code...
$address = $_POST['address'] ?? '';
// ...existing code...

// Update query to include address
$query = "UPDATE students SET 
    fname = ?, 
    mname = ?, 
    lname = ?, 
    ename = ?, 
    age = ?, 
    sex = ?, 
    birthdate = ?, 
    address = ?, 
    year_level = ?, 
    section = ?, 
    email = ?, 
    lrn = ? 
    WHERE user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param(
    "ssssisssssssi", 
    $fname, 
    $mname, 
    $lname, 
    $ename, 
    $age, 
    $sex, 
    $birthdate, 
    $address, 
    $year_level, 
    $section, 
    $email, 
    $lrn, 
    $student_id
);

// ...existing code...
?>
