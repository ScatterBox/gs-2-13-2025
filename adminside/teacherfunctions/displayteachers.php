<?php
include '../adminsidebackends/display_teachers.php';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <!-- Include Material Icons CSS -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />


    <!-- Include custom styles -->
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2">
                        <i class="fa-solid fa-chalkboard-teacher me-2"></i>
                        List of Teachers
                        <small class="text-muted fs-6 ms-2"><?php echo count($teachers); ?> total</small>
                    </h1>
                    <div>
                        <a href="addteacher.php" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus me-2"></i>Add New Teacher
                        </a>
                        <button class="btn btn-success ms-2" onclick="document.getElementById('excelFile').click()">
                            <i class="fas fa-file-excel me-2"></i>Import Excel
                        </button>
                    </div>
                </div>

                <!-- Teachers Table -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <table id="teacherTable" class="table table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Age</th>
                                    <th>Address</th>
                                    <th>Sex</th>
                                    <th>Subjects</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teachers as $index => $teacher): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars("{$teacher['fname']} {$teacher['mname']} {$teacher['lname']} {$teacher['ename']}"); ?></td>
                                        <td><?php echo htmlspecialchars($teacher['age']); ?></td>
                                        <td><?php echo htmlspecialchars($teacher['address']); ?></td>
                                        <td><?php echo htmlspecialchars($teacher['sex']); ?></td>
                                        <td><?php echo htmlspecialchars($teacher['subjects'] ?: 'No subjects'); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary" 
                                                    onclick="editTeacher(<?php echo $teacher['user_id']; ?>)">
                                                    <i class="fas fa-edit"></i> Edit
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

    <!-- Edit Teacher Modal -->
    <div class="modal fade" id="editTeacherModal" tabindex="-1" aria-labelledby="editTeacherModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTeacherModalLabel">Edit Teacher Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTeacherForm" class="needs-validation" novalidate>
                        <input type="hidden" id="editTeacherId" name="teacher_id">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editFname" class="form-label fw-bold">First Name</label>
                                    <input type="text" class="form-control" id="editFname" name="fname" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editMname" class="form-label fw-bold">Middle Name</label>
                                    <input type="text" class="form-control" id="editMname" name="mname" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editLname" class="form-label fw-bold">Last Name</label>
                                    <input type="text" class="form-control" id="editLname" name="lname" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editEname" class="form-label fw-bold">Extension Name</label>
                                    <select class="form-select" id="editEname" name="ename">
                                        <option value="">Select Extension Name</option>
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
                                    <label for="editAge" class="form-label fw-bold">Age</label>
                                    <input type="number" class="form-control" id="editAge" name="age" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editSex" class="form-label fw-bold">Sex</label>
                                    <select class="form-select" id="editSex" name="sex" required>
                                        <option value="">Select Sex</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="editAddress" class="form-label fw-bold">Address</label>
                                    <input type="text" class="form-control" id="editAddress" name="address" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveTeacherChanges()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <input type="file" id="excelFile" name="excelFile" accept=".xls,.xlsx" style="display: none;" onchange="handleFileUpload(event)">

    <script>
        $(document).ready(function() {
            $('#teacherTable').DataTable({
                order: [[1, 'asc']], // Sort by name by default
                pageLength: 10,
                language: {
                    search: "",
                    searchPlaceholder: "Search teachers..."
                },
                columnDefs: [
                    {
                        targets: -1,
                        orderable: false
                    }
                ]
            });

            // Style the DataTables search input
            $('.dataTables_filter input').addClass('form-control');
            $('.dataTables_filter input').css('width', '250px');
        });

        function editTeacher(teacherId) {
            // Fetch teacher data using AJAX
            $.ajax({
                url: 'get_teacher.php',
                type: 'GET',
                data: { teacher_id: teacherId },
                success: function(response) {
                    const teacher = JSON.parse(response);
                    
                    // Populate the modal with teacher data
                    $('#editTeacherId').val(teacher.user_id);
                    $('#editFname').val(teacher.fname);
                    $('#editMname').val(teacher.mname);
                    $('#editLname').val(teacher.lname);
                    $('#editEname').val(teacher.ename);
                    $('#editAge').val(teacher.age);
                    $('#editSex').val(teacher.sex);
                    $('#editAddress').val(teacher.address);
                    
                    // Show the modal
                    $('#editTeacherModal').modal('show');
                },
                error: function() {
                    Swal.fire('Error', 'Failed to fetch teacher data', 'error');
                }
            });
        }

        function saveTeacherChanges() {
            const form = document.getElementById('editTeacherForm');
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const formData = new FormData(form);
            
            $.ajax({
                url: 'update_teacher.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        Swal.fire({
                            title: 'Success',
                            text: 'Teacher information updated successfully',
                            icon: 'success'
                        }).then(() => {
                            $('#editTeacherModal').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', result.message || 'Failed to update teacher information', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to update teacher information', 'error');
                }
            });
        }

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../scripts/showteachers.js"></script>
    <script src="../../scripts/excelupload.js"></script>

</body>

</html>