$(function() {

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
                if(response.success) {
                    console.log('votre token est : ', response.token);
                    $('#login')[0].reset();
                }
                displayMessage(response.type, response.message);
            }
        });
    })

    if(localStorage.getItem('message')) {
        displayMessage('success', localStorage.getItem('message'));
        localStorage.clear();
    }
});