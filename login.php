<?php include 'styles/hui.php'; ?>
<link rel="stylesheet" href="styles/ls.css" />

<?php
include('conn.php');
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: adminside/admin.php");
        exit();
    } elseif ($_SESSION['role'] == 'student') {
        header("Location: studentside/student.php");
        exit();
    } elseif ($_SESSION['role'] == 'teacher') {
        header("Location: teacherside/teacher.php");
        exit();
    }
}

$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = $_POST['uname'];
    $password = $_POST['psw'];

    // Function to check user in a specific table
    function check_user($conn, $table, $usernameOrEmail, $password)
    {
        $sql = "SELECT * FROM $table WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('ss', $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // ✅ Compare passwords as plain text (⚠️ NOT RECOMMENDED)
            if ($password == $user['password']) {
                return $user;
            }
        }
        return false;
    }

    // Check in admins table
    $user = check_user($conn, 'admins', $usernameOrEmail, $password);
    if ($user) {
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = 'admin';
        session_regenerate_id();
        echo "<script>
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('loginTrigger', Date.now()); // Notify other tabs
            window.location.href = 'adminside/admin.php';
        </script>";
        exit();
    }

    // Check in students table
    $user = check_user($conn, 'students', $usernameOrEmail, $password);
    if ($user) {
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = 'student';
        session_regenerate_id();
        echo "<script>
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('loginTrigger', Date.now()); // Notify other tabs
            window.location.href = 'studentside/student.php';
        </script>";
        exit();
    }

    // Check in teachers table
    $user = check_user($conn, 'teachers', $usernameOrEmail, $password);
    if ($user) {
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = 'teacher';
        session_regenerate_id();
        echo "<script>
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('loginTrigger', Date.now()); // Notify other tabs
            window.location.href = 'teacherside/teacher.php';
        </script>";
        exit();
    }

    // If no match found
    $errorMsg = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FGSNHS - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <link rel="icon" type="image/png" href="images/logo.jpg">
    <link rel="stylesheet" href="styles/ls.css">
</head>

<body class="bg-light">
    <div id="login" class="login d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <img src="/gradingsystem/images/logo.jpg" alt="FGSNHS Logo" class="logo-img mb-3">
                                <h2 class="fw-bold text-primary">FGSNHS</h2>
                                <p class="text-muted">Grading System Portal</p>
                            </div>
                            <form id="loginForm" action="login.php" method="post">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="uname" name="uname" placeholder="Username or Email" required>
                                    <label for="uname">Username or Email</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="psw" name="psw" placeholder="Password" required>
                                    <label for="psw">Password</label>
                                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none pe-3 toggle-password">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                    <a class="small text-decoration-none" href="forgot_password.php">Forgot Password?</a>
                                    <button type="submit" class="btn btn-primary px-4" id="loginBtn">
                                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                        Login
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const spinner = loginBtn.querySelector('.spinner-border');
            const errorMsg = "<?php echo $errorMsg; ?>";

            // Password visibility toggle
            document.querySelector('.toggle-password').addEventListener('click', function() {
                const passwordInput = document.getElementById('psw');
                const icon = this.querySelector('i');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });

            // Form submission
            loginForm.addEventListener('submit', function() {
                loginBtn.disabled = true;
                spinner.classList.remove('d-none');
            });

            // Error handling
            if (errorMsg) {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: errorMsg,
                    confirmButtonColor: '#0d6efd'
                }).then(() => {
                    document.getElementById('uname').focus();
                });
            }

            // Check if already logged in
            if (localStorage.getItem('isLoggedIn') === 'true') {
                var role = localStorage.getItem('role');
                if (role === 'admin') {
                    window.location.href = 'adminside/admin.php';
                } else if (role === 'student') {
                    window.location.href = 'studentside/student.php';
                } else if (role === 'teacher') {
                    window.location.href = 'teacherside/teacher.php';
                }
            }

            // Listen for storage events
            window.addEventListener('storage', function(event) {
                if (event.key === 'loginTrigger' || event.key === 'logoutTrigger') {
                    location.reload();
                }
            });
        });
    </script>
</body>

</html>