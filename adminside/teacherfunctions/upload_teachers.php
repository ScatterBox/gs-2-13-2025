<?php
// Debugging: Check if the file exists
if (!file_exists('../../excelhandler/SimpleXLSX.php')) {
    error_log("SimpleXLSX.php not found");
    echo json_encode(['success' => false, 'message' => 'SimpleXLSX.php not found']);
    exit();
}

require '../../excelhandler/SimpleXLSX.php';

use Shuchkin\SimpleXLSX;

session_start();
if ($_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'gradingsystem';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];

    if ($xlsx = SimpleXLSX::parse($file)) {
        $rows = $xlsx->rows();
        $headers = array_shift($rows); // Get the headers

        $errors = [];
        $successCount = 0;

        foreach ($rows as $index => $row) {
            // Map the row data to the corresponding fields
            $data = array_combine($headers, $row);

            // Check if all required fields are present
            if (empty($data['First Name']) || empty($data['Middle Name']) || empty($data['Last Name']) || empty($data['Nickname']) || empty($data['Age']) || empty($data['Sex']) || empty($data['Birthdate']) || empty($data['Address']) || empty($data['Username']) || empty($data['Password']) || empty($data['Email'])) {
                $errors[] = "Row $index: Missing required fields.";
                continue;
            }

            $fname = ucfirst($data['First Name']);
            $mname = ucfirst($data['Middle Name']);
            $lname = ucfirst($data['Last Name']);
            $ename = !empty($data['Extension Name']) ? ucfirst($data['Extension Name']) : ''; // Set default value to empty string
            $nickname = ucfirst($data['Nickname']);
            $age = $data['Age'];
            $sex = ucfirst($data['Sex']);
            $birthdate = $data['Birthdate'];
            $address = ucfirst($data['Address']);
            $username = $data['Username'];
            $password = password_hash($data['Password'], PASSWORD_DEFAULT);
            $email = $data['Email'];

            // Check if a user with the same username, nickname, email, or full name (including ename) already exists
            $checkSql = "SELECT * FROM teachers WHERE username = ? OR nickname = ? OR email = ? OR (fname = ? AND mname = ? AND lname = ? AND ename = ?)";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("sssssss", $username, $nickname, $email, $fname, $mname, $lname, $ename);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                $errors[] = "Row $index: A user with the same username, nickname, email, or full name already exists.";
                $checkStmt->close();
                continue;
            }
            $checkStmt->close();

            // Insert data into the teachers table
            $sql = "INSERT INTO teachers (fname, mname, lname, ename, nickname, age, sex, birthdate, address, username, password, email) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                $errors[] = "Row $index: Failed to prepare statement.";
                error_log("Row $index: Failed to prepare statement.");
                continue;
            }

            $stmt->bind_param("ssssssssssss", $fname, $mname, $lname, $ename, $nickname, $age, $sex, $birthdate, $address, $username, $password, $email);
            if (!$stmt->execute()) {
                $errors[] = "Row $index: Failed to execute statement.";
                error_log("Row $index: Failed to execute statement: " . $stmt->error);
            } else {
                $successCount++;
            }

            $stmt->close();
        }

        $conn->close();

        if (empty($errors)) {
            echo json_encode(['success' => true, 'message' => 'File processed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Some rows failed to process', 'errors' => $errors, 'successCount' => $successCount]);
        }
    } else {
        error_log("SimpleXLSX parse error: " . SimpleXLSX::parseError());
        echo json_encode(['success' => false, 'message' => SimpleXLSX::parseError()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
