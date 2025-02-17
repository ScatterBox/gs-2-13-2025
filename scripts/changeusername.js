$(document).ready(function () {
    $('#changeUsername').click(function () {
        Swal.fire({
            title: 'Change Username',
            input: 'text',
            inputLabel: 'Enter new username',
            inputValue: $('#usernameDisplay').text().trim(), // Trim whitespace here
            showCancelButton: true,
            confirmButtonText: 'Save',
            preConfirm: (newUsername) => {
                if (!newUsername.trim()) {
                    Swal.showValidationMessage('Username cannot be empty');
                }
                return newUsername.trim();
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '', // Add correct backend endpoint
                    type: 'POST',
                    data: { new_username: result.value },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === "success") {
                            Swal.fire('Success', response.message, 'success').then(() => {
                                $('#usernameDisplay').text(result.value);
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to update username.', 'error');
                    }
                });
            }
        });
    });
});
