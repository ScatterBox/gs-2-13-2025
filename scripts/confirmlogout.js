document.addEventListener("DOMContentLoaded", function () {
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
});