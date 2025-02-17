<?php
include '../adminsidebackends/add_students.php';
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
                    <a href="../teacherfunctions/displayteachers.php">
                        <i class="fa-solid fa-chalkboard-teacher"></i> Teachers
                    </a>
                </li>
                <li>
                    <a href="../adminfunctions/displayadmins.php">
                        <i class="fa-solid fa-user-tie"></i> Admin
                    </a>
                </li>
                <li>
                    <a href="displaystudents.php">
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
                        <img src="<?php echo '../../uploads/' . htmlspecialchars($_SESSION['user']['img']) . '?' . time(); ?>"
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
                        Add New Student
                    </h1>
                    <p class="text-muted">Fill in the student's information or upload via Excel</p>
                </div>

                <!-- Student Registration Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-user-graduate me-2"></i>
                            Student Information Form
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="studentForm" class="needs-validation" novalidate>
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
                                            placeholder="Example: Cruz" style="text-transform: capitalize;" required>
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
                                            placeholder="Example: Juanito" style="text-transform: capitalize;" required>
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
                                            <option value="" selected>Select Sex</option>
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
                                            placeholder="Enter complete address" required>
                                        <div class="invalid-feedback">Please enter address.</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pname" class="form-label fw-bold">Guardian Name</label>
                                        <input type="text" class="form-control" id="pname" name="pname" 
                                            placeholder="Enter guardian's name" style="text-transform: capitalize;" required>
                                        <div class="invalid-feedback">Please enter guardian's name.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email" class="form-label fw-bold">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                            placeholder="example@email.com">
                                        <div class="invalid-feedback">Please enter a valid email address.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lrn" class="form-label fw-bold">LRN</label>
                                        <input type="text" class="form-control" id="lrn" name="lrn" 
                                            placeholder="Enter LRN number" required>
                                        <div class="invalid-feedback">Please enter LRN number.</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="year_level" class="form-label fw-bold">Year Level</label>
                                        <select class="form-select" id="year_level" name="year_level" required>
                                            <option value="" selected>Select Year Level</option>
                                            <option value="Grade 9">Grade 9</option>
                                            <option value="Grade 10">Grade 10</option>
                                        </select>
                                        <div class="invalid-feedback">Please select year level.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="section" class="form-label fw-bold">Section</label>
                                        <select class="form-select" id="section" name="section" required>
                                            <option value="" selected>Select Section</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                        </select>
                                        <div class="invalid-feedback">Please select section.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="username" class="form-label fw-bold">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" 
                                            placeholder="Enter username" required>
                                        <div class="invalid-feedback">Please enter username.</div>
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
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Student
                                </button>
                                <button type="reset" class="btn btn-secondary ms-2">
                                    <i class="fas fa-undo me-2"></i>Reset Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Excel Upload Section -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-file-excel me-2"></i>
                            Upload Students via Excel
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="excelUploadForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="excelFile" class="form-label">Choose Excel File</label>
                                <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xlsx, .xls">
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload me-2"></i>Upload Excel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        // Calculate age based on birthdate
        document.getElementById('birthdate').addEventListener('change', function() {
            var birthdate = new Date(this.value);
            var today = new Date();
            var age = today.getFullYear() - birthdate.getFullYear();
            var monthDiff = today.getMonth() - birthdate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }
            
            document.getElementById('age').value = age;
        });

        // Logout confirmation
        document.getElementById('logoutLink').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Logout Confirmation',
                text: "Are you sure you want to logout?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = this.href;
                }
            });
        });
    </script>
</body>

</html>