<?php
include 'adminsidebackends/profileeditor.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Profile</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="../styles/style1.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/png" href="../images/logo.jpg">


</head>

<body>
    <div class="container-fluid">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../images/logo.jpg" alt="logo" />
                <div class="header-text">
                    <h2 class="dashboard-title">
                        <a href="admin.php" class="dashboard-link">
                            <span class="admin-text">Admin</span>
                            <span class="dashboard-text">Dashboard</span>
                        </a>
                    </h2>
                </div>
            </div>
            <ul class="sidebar-links">
                <li>
                    <a href="teacherfunctions/displayteachers.php">
                        <i class="fa-solid fa-chalkboard-teacher"></i> Teachers
                    </a>
                </li>
                <li>
                    <a href="adminfunctions/displayadmins.php">
                        <i class="fa-solid fa-user-tie"></i> Admin
                    </a>
                </li>
                <li>
                    <a href="studentfunctions/displaystudents.php">
                        <i class="fa-solid fa-user-graduate"></i> Students
                    </a>
                </li>


                <h4>
                    <span>Account</span>
                    <div class="menu-separator"></div>
                </h4>
                <li>
                    <a href="../logout.php" id="logoutLink">
                        <span class="material-symbols-outlined">logout</span>Logout
                    </a>
                </li>
            </ul>
            <a href="adminprofile.php" class="user-account-link">
                <div class="user-account">
                    <div class="user-profile">
                        <img src="<?php echo '../uploads/' . htmlspecialchars($_SESSION['user']['img']) . '?' . time(); ?>"
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
            <div class="container py-5">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="profile-image-container mb-3">
                                    <img id="profilePic" src="<?php echo htmlspecialchars($userImg); ?>"
                                        class="rounded-circle" width="180" height="180">
                                </div>
                                <form id="uploadForm" enctype="multipart/form-data">
                                    <input type="file" name="profile_img" id="profile_img" accept="image/*"
                                        style="display: none;">
                                    <button type="button" class="btn btn-primary" id="changeProfile">
                                        <span class="material-symbols-outlined">photo_camera</span> Change Photo
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Bio Section -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Bio</h5>
                                <p id="displayBio" class="text-muted"><?php echo htmlspecialchars($userBio); ?></p>
                                <textarea class="form-control d-none" id="userBio"
                                    rows="4"><?php echo htmlspecialchars($userBio); ?></textarea>
                                <button class="btn btn-outline-primary btn-sm" id="editBio">Change Bio</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Personal Information</h5>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Full Name</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            <?php
                                            echo htmlspecialchars($_SESSION['user']['fname']) . ' ' .
                                                htmlspecialchars($_SESSION['user']['mname']) . ' ' .
                                                htmlspecialchars($_SESSION['user']['lname']) .
                                                (!empty($_SESSION['user']['ename']) ? ' ' . htmlspecialchars($_SESSION['user']['ename']) : '');
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Age</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            <?php echo htmlspecialchars($_SESSION['user']['age'] ?? 'Not set'); ?>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Birthdate</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            <?php echo htmlspecialchars($_SESSION['user']['birthdate'] ?? 'Not set'); ?>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Address</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            <?php echo htmlspecialchars($_SESSION['user']['address'] ?? 'Not set'); ?>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Username</p>
                                    </div>
                                    <div class="col-sm-7">
                                        <p class="text-muted mb-0" id="usernameDisplay">
                                            <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
                                        </p>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-outline-primary btn-sm" id="changeUsername">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                            Change
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Email</p>
                                    </div>
                                    <div class="col-sm-7">
                                        <p class="text-muted mb-0" id="emailDisplay">
                                            <?php echo htmlspecialchars($userEmail); ?>
                                        </p>
                                        <input type="email" class="form-control d-none" id="emailInput"
                                            value="<?php echo htmlspecialchars($userEmail); ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-outline-primary btn-sm" id="changeEmail">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                            Change
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    window.location.href = "../logout.php"; // Redirect to logout
                }
            });
        });
    </script>
    <script src="../scripts/emailchange.js"></script>
    <script src="../scripts/changepic.js"></script>
    <script src="../scripts/changebio.js"></script>
    <script src="../scripts/changeusername.js"></script>
    <script src="../scripts/checkSession.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>
</body>

</html>