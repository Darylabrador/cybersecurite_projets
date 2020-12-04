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


    /**
     * Dynamic progress bar
     * @param {*} strength 
     */
    const progressBarStrength = (strength) => {
        $('#passStrengthBar').attr({
            style: `width: ${strength}%`,
            "aria-valuenow": strength,
        })

        switch (strength) {
            case 100:
                $('#passStrengthBar').addClass('bg-success')
                $('#passStrengthBar').removeClass('bg-warning')
                $('#passStrengthBar').removeClass('bg-danger')
                break;
            case 40:
                $('#passStrengthBar').addClass('bg-warning')
                $('#passStrengthBar').removeClass('bg-success')
                $('#passStrengthBar').removeClass('bg-danger')
                break;
            case 10:
                $('#passStrengthBar').addClass('bg-danger')
                $('#passStrengthBar').removeClass('bg-success')
                $('#passStrengthBar').removeClass('bg-warning')
                break;
            default:
                break;
        }

    }

    /**
     * Display password strength
     */
    const displayStrength = (password) => {
        var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
        var mediumRegex = new RegExp("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})");

        if(strongRegex.test(password)) {
            progressBarStrength(100);
        } else if(mediumRegex.test(password)) {
            progressBarStrength(40);
        } else {
            progressBarStrength(10);
        }
    }


    $('#password').on('keyup', function(evt){
        let chosenPass = evt.target.value;
        if(chosenPass != ""){
            $('#passStrength').removeClass('d-none');
        } else {
            $('#passStrength').addClass('d-none');
        }
        displayStrength(chosenPass);
    });


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
                    displayMessage(response.type, response.message);
                }
            }
        });
    });
});