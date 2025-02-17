<?php
include '../adminsidebackends/add_teachers.php';
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

    <!-- Include Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Include DataTables CSS -->
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.css" rel="stylesheet">

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>

    <!-- Include SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Include Material Icons CSS -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="stylesheet" href="../../styles/style1.css">
    <link rel="stylesheet" href="../tablestyles/style.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../images/logo.jpg">
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
                    <a href="displayteachers.php">
                        <i class="fa-solid fa-chalkboard-teacher"></i> Teachers
                    </a>
                </li>
                <li>
                    <a href="../adminfunctions/displayadmins.php">
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
            <div class="container py-4">
                <!-- Page Header -->
                <div class="mb-4">
                    <h1 class="h2">
                        <i class="fa-solid fa-user-plus me-2"></i>
                        Add New Teacher
                    </h1>
                    <p class="text-muted">Fill in the teacher's information or upload via Excel</p>
                </div>

                <!-- Teacher Registration Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-user-tie me-2"></i>
                            Teacher Information Form
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="teacherForm" class="needs-validation" novalidate>
                            <!-- Personal Information -->
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fname" class="form-label fw-bold">First Name</label>
                                        <input type="text" class="form-control" id="fname" name="fname"
                                            placeholder="Example: Juan" style="text-transform: capitalize;" required>
                                        <div class="invalid-feedback">Please enter first name.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mname" class="form-label fw-bold">Middle Name</label>
                                        <input type="text" class="form-control" id="mname" name="mname"
                                            placeholder="Example: Dela" style="text-transform: capitalize;" required>
                                        <div class="invalid-feedback">Please enter middle name.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lname" class="form-label fw-bold">Last Name</label>
                                        <input type="text" class="form-control" id="lname" name="lname"
                                            placeholder="Example: Crunchy" style="text-transform: capitalize;" required>
                                        <div class="invalid-feedback">Please enter last name.</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ename" class="form-label fw-bold">Extension Name</label>
                                        <select class="form-select" id="ename" name="ename">
                                            <option value="" selected>Select Extension Name</option>
                                            <option value="Sr.">Sr.</option>
                                            <option value="Jr.">Jr.</option>
                                            <option value="II">II</option>
                                            <option value="III">III</option>
                                            <option value="IV">IV</option>
                                            <option value="V">V</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nickname" class="form-label fw-bold">Display Name</label>
                                        <input type="text" class="form-control" id="nickname" name="nickname"
                                            placeholder="Example: Tutu" style="text-transform: capitalize;" required>
                                        <div class="invalid-feedback">Please enter display name.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="birthdate" class="form-label fw-bold">Birthdate</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                                        <div class="invalid-feedback">Please select birthdate.</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="age" class="form-label fw-bold">Age</label>
                                        <input type="number" class="form-control" id="age" name="age"
                                            placeholder="Auto-calculated" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sex" class="form-label fw-bold">Sex</label>
                                        <select class="form-select" id="sex" name="sex" required>
                                            <option value="" disabled selected>Select Sex</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        <div class="invalid-feedback">Please select sex.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address" class="form-label fw-bold">Address</label>
                                        <input type="text" class="form-control" id="address" name="address"
                                            placeholder="Complete address" required>
                                        <div class="invalid-feedback">Please enter address.</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="username" class="form-label fw-bold">Username</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            placeholder="Example: @JuanFGSNHS" required>
                                        <div class="invalid-feedback">Please enter username.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email" class="form-label fw-bold">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Example: juan@example.com" required>
                                        <div class="invalid-feedback">Please enter a valid email.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password" class="form-label fw-bold">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Enter password" required>
                                        <div class="invalid-feedback">Please enter password.</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group text-end mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fa-solid fa-user-plus me-2"></i>
                                            Add Teacher
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Excel Upload Section -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-file-excel me-2"></i>
                            Bulk Upload Teachers
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="uploadForm" method="POST" enctype="multipart/form-data" action="upload_teachers.php" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="excel_file" class="form-label fw-bold">Excel File</label>
                                        <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xls,.xlsx" required>
                                        <div class="invalid-feedback">Please select an Excel file.</div>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-group w-100">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fa-solid fa-upload me-2"></i>
                                            Upload Excel File
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calculate age based on birthdate
        document.getElementById('birthdate').addEventListener('change', function() {
            const birthdate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthdate.getFullYear();
            const monthDiff = today.getMonth() - birthdate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }
            
            document.getElementById('age').value = age;
        });

        // Form validation and submission
        document.getElementById('teacherForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            if (!this.checkValidity()) {
                event.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);
            
            fetch('../adminsidebackends/add_teachers.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('Success')) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Teacher added successfully',
                        icon: 'success',
                        confirmButtonColor: '#0d6efd'
                    }).then(() => {
                        this.reset();
                        this.classList.remove('was-validated');
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data,
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong while adding the teacher.',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            });
        });

        // Excel upload form validation
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            this.classList.add('was-validated');
        });

        // Logout confirmation
        document.getElementById('logoutLink').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Logout?',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = this.href;
                }
            });
        });
    </script>
</body>

</html>