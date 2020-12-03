$(function() {
    $('#login').on('submit', function(evt){
        evt.preventDefault();

        let dataSend = {
            email: $('#email').val(),
            password: $('#password').val()
        }

        $.ajax({
            type: "POST",
            url: "/api/login",
            data: dataSend,
            dataType: "json",
            success: function (response) {
                console.log(response)
                // $('#login')[0].reset();
            }
        });
    })
});