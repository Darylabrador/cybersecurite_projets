$(function(){
    $('#flashMessage').on('submit', function(evt){
        evt.preventDefault();
        let dataSend = {
            email: $('#email').val(),
            password: $('#password').val(),
            passwordConfirm: $('#passwordConfirm').val(),
        }

        $.ajax({
            type: "POST",
            url: "/api/inscription",
            data: dataSend,
            dataType: "json",
            success: function (response) {
                console.log(response)
                // $('#flashMessage')[0].reset();
            }
        });
    });

    console.log('fichier inscription js')
});