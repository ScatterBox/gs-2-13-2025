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

    <!-- Include Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Include DataTables CSS -->
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.css" rel="stylesheet">

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.min.js"></script>

    <!-- Include SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Include Material Icons CSS -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

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
            <div class="container py-4">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2">
                        <i class="fa-solid fa-user-tie me-2"></i>
                        List of Admins
                        <small class="text-muted fs-6 ms-2"><?php echo $result ? $result->num_rows : 0; ?> total</small>
                    </h1>
                    <div>
                        <a href="addadmin.php" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus me-2"></i>Add New Admin
                        </a>
                        <button class="btn btn-success ms-2" onclick="document.getElementById('excelFile').click()">
                            <i class="fas fa-file-excel me-2"></i>Import Excel
                        </button>
                    </div>
                </div>

                <!-- Admins Table -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <table id="adminTable" class="table table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Age</th>
                                    <th>Birthdate</th>
                                    <th>Sex</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Address</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $fullname = trim($row['fname'] . " " . $row['mname'] . " " . $row['lname'] . " " . $row['ename']);
                                        ?>
                                        <tr>
                                            <td><?php echo $row['user_id']; ?></td>
                                            <td><?php echo htmlspecialchars($fullname); ?></td>
                                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                                            <td><?php echo htmlspecialchars($row['birthdate']); ?></td>
                                            <td><?php echo htmlspecialchars($row['sex']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-primary" 
                                                        onclick="editAdmin(<?php echo $row['user_id']; ?>)">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='9' class='text-center'>No admins found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="file" id="excelFile" name="excelFile" accept=".xls,.xlsx" style="display: none;" onchange="handleFileUpload(event)">

    <!-- Edit Admin Modal -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAdminModalLabel">Edit Admin Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAdminForm" class="needs-validation" novalidate>
                        <input type="hidden" id="editAdminId" name="admin_id">
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
                                    <label for="editBirthdate" class="form-label fw-bold">Birthdate</label>
                                    <input type="date" class="form-control" id="editBirthdate" name="birthdate" required>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editEmail" class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" id="editEmail" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editUsername" class="form-label fw-bold">Username</label>
                                    <input type="text" class="form-control" id="editUsername" name="username" required>
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
                    <button type="button" class="btn btn-primary" onclick="saveAdminChanges()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#adminTable').DataTable({
                order: [[1, 'asc']], // Sort by name by default
                pageLength: 10,
                language: {
                    search: "",
                    searchPlaceholder: "Search admins..."
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

        function editAdmin(adminId) {
            // Fetch admin data using AJAX
            $.ajax({
                url: 'get_admin.php',
                type: 'GET',
                data: { admin_id: adminId },
                success: function(response) {
                    const admin = JSON.parse(response);
                    
                    // Populate the modal with admin data
                    $('#editAdminId').val(admin.user_id);
                    $('#editFname').val(admin.fname);
                    $('#editMname').val(admin.mname);
                    $('#editLname').val(admin.lname);
                    $('#editEname').val(admin.ename);
                    $('#editBirthdate').val(admin.birthdate);
                    $('#editAge').val(admin.age);
                    $('#editSex').val(admin.sex);
                    $('#editEmail').val(admin.email);
                    $('#editUsername').val(admin.username);
                    $('#editAddress').val(admin.address);
                    
                    // Show the modal
                    $('#editAdminModal').modal('show');
                },
                error: function() {
                    Swal.fire('Error', 'Failed to fetch admin data', 'error');
                }
            });
        }

        function saveAdminChanges() {
            const form = document.getElementById('editAdminForm');
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const formData = new FormData(form);
            
            $.ajax({
                url: 'update_admin.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    try {
                        const result = JSON.parse(response);
                        if (result.success) {
                            $('#editAdminModal').modal('hide');
                            Swal.fire('Success', 'Admin information updated successfully', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', result.message || 'Failed to update admin', 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error', 'Failed to process server response', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to update admin information', 'error');
                }
            });
        }

        // Calculate age when birthdate changes
        document.getElementById('editBirthdate').addEventListener('change', function() {
            var birthdate = new Date(this.value);
            var today = new Date();
            var age = today.getFullYear() - birthdate.getFullYear();
            var monthDiff = today.getMonth() - birthdate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }
            
            document.getElementById('editAge').value = age;
        });
    </script>
</body>

</html>