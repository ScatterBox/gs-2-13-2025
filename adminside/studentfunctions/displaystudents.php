<?php
include '../adminsidebackends/display_students.php';
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

    <!-- Include custom styles -->
    <link rel="stylesheet" href="../../styles/style1.css">
    <link rel="stylesheet" href="../tablestyles/style.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../images/logo.jpg">

    <script>
        function notifyLogout() {
            localStorage.setItem('isLoggedIn', 'false');
            localStorage.setItem('logoutTrigger', Date.now());
        }

        window.addEventListener('storage', function(event) {
            if (event.key === 'logoutTrigger') {
                window.location.reload();
            }
        });
    </script>
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2">
                        <i class="fa-solid fa-user-graduate me-2"></i>
                        List of Students
                        <small class="text-muted fs-6 ms-2"><?php echo count($students); ?> total</small>
                    </h1>
                    <div>
                        <a href="addstudents.php" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus me-2"></i>Add New Student
                        </a>
                        <button class="btn btn-success ms-2" onclick="document.getElementById('excelFile').click()">
                            <i class="fas fa-file-excel me-2"></i>Import Excel
                        </button>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <table id="studentTable" class="table table-hover table-sm" style="width:100%; font-size: 0.875rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Birthdate</th>
                                    <th>Address</th>
                                    <th>Year Level</th>
                                    <th>Section</th>
                                    <th>Subjects</th>
                                    <th>Email</th>
                                    <th>LRN</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $index => $student): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($student['fullname']); ?></td>
                                        <td><?php echo htmlspecialchars($student['age']); ?></td>
                                        <td><?php echo htmlspecialchars($student['sex']); ?></td>
                                        <td><?php echo htmlspecialchars($student['birthdate']); ?></td>
                                        <td><?php echo htmlspecialchars($student['address']); ?></td>
                                        <td>Grade <?php echo htmlspecialchars($student['year_level']); ?></td>
                                        <td><?php echo htmlspecialchars($student['section']); ?></td>
                                        <td><?php echo htmlspecialchars($student['subjects']); ?></td>
                                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                                        <td><?php echo htmlspecialchars($student['lrn']); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-sm btn-primary" 
                                                    onclick="editStudent(<?php echo $student['user_id']; ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deleteStudent(<?php echo $student['user_id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="file" id="excelFile" name="excelFile" accept=".xls,.xlsx" style="display: none;" onchange="handleFileUpload(event)">

    <!-- Edit Student Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel">Edit Student Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStudentForm" class="needs-validation" novalidate>
                        <input type="hidden" id="editStudentId" name="student_id">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="editFname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="editFname" name="fname" required>
                                <div class="invalid-feedback">Please enter first name.</div>
                            </div>
                            <div class="col-md-3">
                                <label for="editMname" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="editMname" name="mname">
                            </div>
                            <div class="col-md-3">
                                <label for="editLname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="editLname" name="lname" required>
                                <div class="invalid-feedback">Please enter last name.</div>
                            </div>
                            <div class="col-md-3">
                                <label for="editEname" class="form-label">Name Extension</label>
                                <input type="text" class="form-control" id="editEname" name="ename">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="editAge" class="form-label">Age</label>
                                <input type="number" class="form-control" id="editAge" name="age" required readonly>
                                <div class="invalid-feedback">Please enter age.</div>
                            </div>
                            <div class="col-md-3">
                                <label for="editSex" class="form-label">Sex</label>
                                <select class="form-select" id="editSex" name="sex" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <div class="invalid-feedback">Please select sex.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="editBirthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="editBirthdate" name="birthdate" required>
                                <div class="invalid-feedback">Please enter birthdate.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="editAddress" class="form-label">Address</label>
                                <textarea class="form-control" id="editAddress" name="address" required rows="2"></textarea>
                                <div class="invalid-feedback">Please enter address.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="editYearLevel" class="form-label">Year Level</label>
                                <select class="form-select" id="editYearLevel" name="year_level" required disabled>
                                    <option value="">Select Year Level</option>
                                    <option value="Grade 9">Grade 9</option>
                                    <option value="Grade 10">Grade 10</option>
                                </select>
                                <div class="invalid-feedback">Please select year level.</div>
                            </div>
                            <div class="col-md-4">
                                <label for="editSection" class="form-label">Section</label>
                                <input type="text" class="form-control" id="editSection" name="section" required readonly>
                                <div class="invalid-feedback">Please enter section.</div>
                            </div>
                            <div class="col-md-4">
                                <label for="editLRN" class="form-label">LRN</label>
                                <input type="text" class="form-control" id="editLRN" name="lrn" required pattern="[0-9]{12}" readonly>
                                <div class="invalid-feedback">Please enter a valid 12-digit LRN.</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveStudentChanges()">Save Changes</button>
                </div>
            </div>
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
                    notifyLogout(); // Notify other tabs
                    window.location.href = "../../logout.php"; // Redirect to logout
                }
            });
        });
    </script>
    <script src="../../scripts/showstudents.js"></script>
    <script src="../../scripts/excelupload.js"></script>

    <script>
        $(document).ready(function() {
            // Check if DataTable is already initialized
            if (!$.fn.DataTable.isDataTable('#studentTable')) {
                $('#studentTable').DataTable({
                    "pageLength": 10,
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    "order": [[1, "asc"]],
                    "language": {
                        "search": "",
                        "searchPlaceholder": "Search students..."
                    },
                    "columnDefs": [
                        {
                            "targets": -1,
                            "orderable": false
                        }
                    ],
                    "responsive": true,
                    "scrollX": true
                });

                // Style the DataTables search input
                $('.dataTables_filter input').addClass('form-control form-control-sm');
                $('.dataTables_filter input').css('width', '200px');
                
                // Make length menu smaller
                $('.dataTables_length select').addClass('form-select form-select-sm');
            }

            // Function to calculate age from birthdate
            function calculateAge(birthdate) {
                const today = new Date();
                const birthDate = new Date(birthdate);
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                return age;
            }

            // Make functions globally accessible
            window.editStudent = function(studentId) {
                $.ajax({
                    url: 'get_student.php',
                    type: 'GET',
                    data: { student_id: studentId },
                    success: function(response) {
                        try {
                            const student = JSON.parse(response);
                            console.log('Student data:', student); // Debug log
                            
                            // Populate the edit form
                            $('#editStudentId').val(student.user_id);
                            $('#editFname').val(student.fname);
                            $('#editMname').val(student.mname);
                            $('#editLname').val(student.lname);
                            $('#editEname').val(student.ename);
                            $('#editSex').val(student.sex);
                            $('#editBirthdate').val(student.birthdate);
                            $('#editAddress').val(student.address || ''); // Handle null/undefined
                            $('#editYearLevel').val(student.year_level);
                            $('#editSection').val(student.section);
                            $('#editEmail').val(student.email);
                            $('#editLRN').val(student.lrn);

                            // Calculate and set age
                            if (student.birthdate) {
                                const age = calculateAge(student.birthdate);
                                $('#editAge').val(age);
                            }

                            // Show the modal
                            $('#editStudentModal').modal('show');
                        } catch (e) {
                            console.error('Error parsing student data:', e); // Debug log
                            Swal.fire('Error', 'Failed to parse student data', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error); // Debug log
                        Swal.fire('Error', 'Failed to fetch student data', 'error');
                    }
                });
            };

            // Add event listener for birthdate changes
            $('#editBirthdate').on('change', function() {
                const birthdate = $(this).val();
                if (birthdate) {
                    const age = calculateAge(birthdate);
                    $('#editAge').val(age);
                } else {
                    $('#editAge').val('');
                }
            });

            window.saveStudentChanges = function() {
                const form = document.getElementById('editStudentForm');
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                const formData = new FormData(form);
                
                // Log form data for debugging
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                $.ajax({
                    url: 'update_student.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        try {
                            console.log('Server response:', response); // Debug log
                            const result = JSON.parse(response);
                            if (result.success) {
                                $('#editStudentModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Student information updated successfully',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', result.message || 'Failed to update student', 'error');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e); // Debug log
                            Swal.fire('Error', 'Failed to process server response', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error); // Debug log
                        Swal.fire('Error', 'Failed to update student information', 'error');
                    }
                });
            };

            window.deleteStudent = function(studentId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete_student.php',
                            type: 'POST',
                            data: { student_id: studentId },
                            success: function(response) {
                                try {
                                    const result = JSON.parse(response);
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Deleted!',
                                            text: 'Student has been deleted.',
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire('Error', result.message || 'Failed to delete student', 'error');
                                    }
                                } catch (e) {
                                    Swal.fire('Error', 'Failed to process server response', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Failed to delete student', 'error');
                            }
                        });
                    }
                });
            };
        });
    </script>
</body>

</html>