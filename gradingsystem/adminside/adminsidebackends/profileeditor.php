<?php
session_start();
require_once '../conn.php'; // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];

// Determine user role based on table membership
$role = '';
$tables = ['admins' => 'admin', 'teachers' => 'teacher', 'students' => 'student'];

foreach ($tables as $table => $user_role) {
    $query = "SELECT user_id, bio, img, email FROM $table WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $role = $user_role;
        $userBio = $row['bio'] ?? '';
        $userImg = !empty($row['img']) ? '../uploads/' . $row['img'] : '../images/default-profile.jpg';
        $userEmail = $row['email'] ?? '';
        break;
    }
    $stmt->close();
}

if ($role === '') {
    die("Error: User role cannot be determined.");
}

// Handle image upload if a file is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_img'])) {
    $target_dir = "../uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0775, true);
    }

    $imageFileType = strtolower(pathinfo($_FILES["profile_img"]["name"], PATHINFO_EXTENSION));
    $new_filename = "{$role}_{$user_id}_" . time() . "." . $imageFileType;
    $target_file = $target_dir . $new_filename;

    if (in_array($imageFileType, ['jpg', 'jpeg', 'png']) && move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
        $sql = "UPDATE $table SET img = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_filename, $user_id);

        if ($stmt->execute()) {
            $_SESSION['user']['img'] = $new_filename;
            echo json_encode(["status" => "success", "filename" => $new_filename]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database update failed."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "File upload failed or invalid format."]);
    }
    exit();
}

// Handle username change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_username'])) {
    $new_username = trim($_POST['new_username']);

    if (!empty($new_username)) {
        $sql = "UPDATE $table SET username = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_username, $user_id);

        if ($stmt->execute()) {
            $_SESSION['user']['username'] = $new_username;
            echo json_encode(["status" => "success", "message" => "Username updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update username."]);
        }
        $stmt->close();
    }
    exit();
}

// Handle bio change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_bio'])) {
    $new_bio = trim($_POST['new_bio']);
    $sql = "UPDATE $table SET bio = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_bio, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Bio updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update bio."]);
    }
    $stmt->close();
    exit();
}

// Handle email change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_email'])) {
    $new_email = trim($_POST['new_email']);

    if (!empty($new_email)) {
        $sql = "UPDATE $table SET email = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_email, $user_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Email updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update email."]);
        }
        $stmt->close();
    }
    exit();
}


// Fetch user image
$userImg = !empty($_SESSION['user']['img']) ? '../uploads/' . $_SESSION['user']['img'] : '../images/default-profile.jpg';
if (!file_exists(dirname(__FILE__) . '/../uploads/' . basename($userImg))) {
    $userImg = '../images/default-profile.jpg';
}
?>