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

    <script src="../../scripts/confirmlogout.js"></script>

    <!-- Include custom styles -->
    <link rel="stylesheet" href="../../styles/style1.css">
    <link rel="stylesheet" href="../tablestyles/style.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../images/logo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


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
                        <span class="material-symbols-outlined">logout</span> Logout
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
            <h3>Student Submitter Form</h3>
            <div class="registration-form">
                <form id="studentForm" method="POST">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fname">First Name:</label>
                                <input type="text" class="form-control item" id="fname" name="fname"
                                    placeholder="Example: Juan" required style="text-transform: capitalize;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mname">Middle Name:</label>
                                <input type="text" class="form-control item" id="mname" name="mname"
                                    placeholder="Example: Dela" required style="text-transform: capitalize;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lname">Last Name:</label>
                                <input type="text" class="form-control item" id="lname" name="lname"
                                    placeholder="Example: Crunchy" required style="text-transform: capitalize;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ename">Extension Name:</label>
                                <select class="form-control item" id="ename" name="ename">
                                    <option value="" disabled selected>Select Extension Name</option>
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
                                <label for="pname">Guardian:</label>
                                <input type="text" class="form-control item" id="pname" name="pname"
                                    placeholder="Your parent's name" required style="text-transform: capitalize;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control item" id="email" name="email"
                                    placeholder="Example: jpg@gmail.com">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lrn">Lrn:</label>
                                <input type="text" class="form-control item" id="lrn" name="lrn"
                                    placeholder="Example: LRN" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nickname"> Account Display Name:</label>
                                <input type="text" class="form-control item" id="nickname" name="nickname"
                                    placeholder="Example: Tutu" required style="text-transform: capitalize;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="birthdate">Birthdate:</label>
                                <input type="date" class="form-control item" id="birthdate" name="birthdate" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="age">Age:</label>
                                <input type="number" class="form-control item" id="age" name="age"
                                    placeholder="Example: 12" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sex">Sex:</label>
                                <select class="form-control item" id="sex" name="sex" required>
                                    <option value="" disabled selected>Select Sex</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control item" id="address" name="address"
                                    placeholder="Example: Purok. Pinetree, Brgy. Oringao, Kabankalan City, Negros Occidental."
                                    required style="text-transform: capitalize;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control item" id="username" name="username"
                                    placeholder="Example: @JuanFGSNHS" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control item" id="password" name="password"
                                    placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="year_level">Year Level:</label>
                                <select class="form-control item" id="year_level" name="year_level" required>
                                    <option value="" disabled selected>Select Year Level</option>
                                    <option value="Grade 9">Grade 9</option>
                                    <option value="Grade 10">Grade 10</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="section">Section:</label>
                                <input type="text" class="form-control item" id="section" name="section"
                                    placeholder="Example: Sardonyx" required style="text-transform: capitalize;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">Create Student Account</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Form for uploading Excel file -->
                <h3 style="margin-top: 10px;">Upload Students via Excel</h3>
                <form id="uploadForm" method="POST" enctype="multipart/form-data" action="upload.php">
                    <div class="form-group">
                        <label for="file">Select Excel File:</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Upload</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Get the form element
        var form = document.getElementById('studentForm');

        // Attach a submit event handler to the form
        form.addEventListener('submit', function (event) {
            // Prevent the form from being submitted normally
            event.preventDefault();

            // Ask for confirmation before submitting
            Swal.fire({
                title: 'Is the information correct?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit it!',
                cancelButtonText: 'No, review it'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a new FormData object from the form
                    var formData = new FormData(form);

                    // Use AJAX to submit the form data
                    var request = new XMLHttpRequest();
                    request.open('POST', window.location.href);
                    request.onreadystatechange = function () {
                        if (request.readyState === 4 && request.status === 200) {
                            // The request has completed successfully
                            if (this.responseText.includes('Error: A user with the same username already exists.')) {
                                Swal.fire(
                                    'Error!',
                                    'A user with the same username already exists.',
                                    'error'
                                );
                            } else if (this.responseText.includes('Error: A user with the same full name already exists.')) {
                                Swal.fire(
                                    'Error!',
                                    'A user with the same full name already exists.',
                                    'error'
                                );
                            } else {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'New record created successfully',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.reload();
                                    }
                                });

                                // Reset the form fields
                                form.reset();
                            }
                        }
                    };
                    request.send(formData);
                }
            });
        });

        // Handle the upload form submission
        var uploadForm = document.getElementById('uploadForm');
        uploadForm.addEventListener('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(uploadForm);

            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'addstudents.php';
                        }
                    });
                } else {
                    let errorMessage = data.message;
                    if (data.errors) {
                        errorMessage += '\n' + data.errors.join('\n');
                    }
                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'addstudents.php';
                        }
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while processing your request.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'addstudents.php';
                    }
                });
            });
        });

        // LOGOUT CONFIRMATION   
        document.addEventListener("DOMContentLoaded", function () {
            var logoutLink = document.getElementById("logoutLink");

            if (logoutLink) {
                logoutLink.addEventListener("click", function (event) {
                    event.preventDefault(); // Prevent default logout action

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
                            window.location.href = logoutLink.href; // Proceed to logout
                        }
                    });
                });
            }
        });

    </script>

    <script src="../../scripts/birthscript.js"></script>
</body>

</html>