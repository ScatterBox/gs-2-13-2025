$(document).ready(function () {
    $('#changeEmail').click(function () {
        Swal.fire({
            title: 'Change Email',
            input: 'email',
            inputLabel: 'Enter new email',
            inputValue: $('#emailDisplay').text(),
            showCancelButton: true,
            confirmButtonText: 'Save',
            preConfirm: (newEmail) => {
                if (!newEmail.trim()) {
                    Swal.showValidationMessage('Email cannot be empty');
                }
                return newEmail.trim();
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '',
                    type: 'POST',
                    data: { new_email: result.value },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === "success") {
                            Swal.fire('Success', response.message, 'success').then(() => {
                                $('#emailDisplay').text(result.value);
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to update email.', 'error');
                    }
                });
            }
        });
    });
});