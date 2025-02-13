<?php
include '../adminsidebackends/display_admins.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Florentino Galang Sr. National High School Grading System</title>

    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

    <!-- Include DataTables CSS -->
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.css" rel="stylesheet">

    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Include Material Icons CSS -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Include custom styles -->
    <link rel="stylesheet" href="../../styles/style1.css">
    <link rel="stylesheet" href="../tablestyles/style.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../images/logo.jpg">

    <!-- Include Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>

    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container-fluid">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../images/logo.jpg" alt="logo" />
                <div class="header-text">
                    <h2 class="dashboard-title">
                        <a href="../admin.php" class="dashboard-link">
                            <span class="admin-text">Admin</span>
                            <span class="dashboard-text">Dashboard</span>
                        </a>
                    </h2>
                </div>
            </div>
            <ul class="sidebar-links">
                <li>
                    <a href="../teacherfunctions/displayteachers.php">
                        <i class="fa-solid fa-chalkboard-teacher"></i> Teachers
                    </a>
                </li>
                <li>
                    <a href="displayadmins.php">
                        <i class="fa-solid fa-user-tie"></i> Admin
                    </a>
                </li>
                <li>
                    <a href="../studentfunctions/displaystudents.php">
                        <i class="fa-solid fa-user-graduate"></i> Students
                    </a>
                </li>

                <h4>
                    <span>Account</span>
                    <div class="menu-separator"></div>
                </h4>
                <li>
                    <a href="../../logout.php" id="logoutLink">
                        <span class="material-symbols-outlined">logout</span>Logout
                    </a>
                </li>
            </ul>
            <a href="../adminprofile.php" class="user-account-link">
                <div class="user-account">
                    <div class="user-profile">
                        <img src="<?php echo '../../uploads/' . htmlspecialchars($_SESSION['user']['img']); ?>"
                            alt="Profile Image" />
                        <div class="user-detail">
                            <h3><?php echo htmlspecialchars($_SESSION['user']['nickname']); ?></h3>
                            <span>Admin's Profile</span>
                        </div>
                    </div>
                </div>
            </a>

        </aside>

        <!-- Main content -->
        <div class="col-md-9" id="mainContent">
            <h1 style="text-align: center;">List of Admins</h1>
            <a href="addadmin.php"
                style="margin-bottom: 20px; padding: 10px 20px; font-size: 16px; color: white; background-color: #007BFF; text-decoration: none; border: none; border-radius: 5px; cursor: pointer; display: inline-block;">
                Add New Admin
            </a>
            <label for="excelFile"
                style="padding: 10px 20px; font-size: 16px; color: white; background-color: #28A745; text-decoration: none; border: none; border-radius: 5px; cursor: pointer; display: inline-block;">
                Excel Import
            </label>
            <input type="file" id="excelFile" name="excelFile" accept=".xls,.xlsx" style="display: none;"
                onchange="handleFileUpload(event)">

            <table id="adminTable" class="table table-striped smaller-table" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Age</th>
                        <th>Birthdate</th>
                        <th>Sex</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        $count = 1;
                        while ($row = $result->fetch_assoc()) {
                            // Construct full name (include ename only if it's not empty)
                            $fullname = $row['fname'] . " " . $row['mname'] . " " . $row['lname'];
                            if (!empty($row['ename'])) {
                                $fullname .= " " . $row['ename'];
                            }

                            echo "<tr>
                                <th scope='row'>{$count}</th>
                                <td>{$fullname}</td>
                                <td>{$row['age']}</td>
                                <td>{$row['birthdate']}</td>
                                <td>{$row['sex']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['address']}</td>
                            </tr>";
                            $count++;
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No admins found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById("logoutLink").addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default link action

            Swal.fire({
                title: "Are you sure?",
                text: "You will be logged out of your account!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, log me out!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../../logout.php"; // Redirect to logout
                }
            });
        });
    </script>
    <script src="../../scripts/excelupload.js"></script>
    <script src="../../scripts/showadmins.js"></script>

</body>

</html>