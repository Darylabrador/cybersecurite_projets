$(function(){

    /**
     * Display message on user interface
     * @param {String} type 
     * @param {String} message 
     */
    const displayMessage = (type, message) => {
        $('#flashMessage').html(`
            <div class="w-100 mx-auto mt-2">
                <div class="alert alert-${type} alert-dismissible fade show mt-0" role="alert">
                    <strong> ${message} </strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        `);
    }

    $('#inscription').on('submit', function(evt){
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
                if (response.success) {
                    localStorage.setItem('message', response.message)
                    location.href = '/';
                    $('#inscription')[0].reset();
                } else {
                    displayMessage('danger', response.message);
                }
            }
        });
    });
});