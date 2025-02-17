$(document).ready(function () {
    $('#changeProfile').click(function () {
        $('#profile_img').click();
    });

    $('#profile_img').change(function () {
        var formData = new FormData();
        formData.append("profile_img", $("#profile_img")[0].files[0]);

        $.ajax({
            url: '', // Same file
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire('Success', 'Profile picture updated!', 'success').then(() => {
                        var newSrc = "../uploads/" + response.filename + "?" + new Date().getTime();
                        $('#profilePic').attr("src", newSrc);
                        $('.user-profile img').attr("src", newSrc);
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Upload failed.', 'error');
            }
        });
    });
});