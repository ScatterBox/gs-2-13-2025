$(document).ready(function () {
    $('#editBio').click(function () {
        if ($('#userBio').hasClass('d-none')) {
            $('#displayBio').addClass('d-none');
            $('#userBio').removeClass('d-none').focus();
            $('#editBio').text('Save Bio');
        } else {
            let newBio = $('#userBio').val().trim();
            $.ajax({
                url: '',
                type: 'POST',
                data: { new_bio: newBio },
                dataType: 'json',
                success: function (response) {
                    if (response.status === "success") {
                        $('#displayBio').text(newBio).removeClass('d-none');
                        $('#userBio').addClass('d-none');
                        $('#editBio').text('Change Bio');
                        Swal.fire('Success', response.message, 'success');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Failed to update bio.', 'error');
                }
            });
        }
    });
});