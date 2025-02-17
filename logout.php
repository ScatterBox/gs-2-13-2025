<?php
session_start();
session_unset();
session_destroy();

echo "<script>
    localStorage.setItem('isLoggedIn', 'false');
    localStorage.setItem('logoutTrigger', Date.now()); // Notify other tabs
    localStorage.removeItem('role');
    localStorage.removeItem('user_id');
    window.location.href = 'login.php';
</script>";
exit();
?>
