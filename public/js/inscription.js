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
                $('#passwordInfo').html(`
                <div style="font-size: 11px !important" class="mt-2">
                    <h6 style="font-size: 11px !important" class="font-weight-bold"> Mot de passe fort </h6>
                </div>`)
                break;
            case 40:
                $('#passStrengthBar').addClass('bg-warning')
                $('#passStrengthBar').removeClass('bg-success')
                $('#passStrengthBar').removeClass('bg-danger')
                $('#passwordInfo').html(`
                <div style="font-size: 11px !important" class="mt-2">
                    <h6 style="font-size: 11px !important" class="font-weight-bold"> Mot de passe moyen </h6>
                    <p> Pour avoir un mot de passe fort </p>
                    <ul class="w-100 pl-4">
                        <li> Au moins 2 minuscules consécutifs </li>
                        <li> Au moins 2 majuscules consécutifs </li>
                        <li> Au moins 2 chiffres consécutifs </li>
                        <li> Au moins 1 caractère spécial </li>
                        <li> Mot de passe de 8 caractères ou plus </li>
                    </ul>
                </div>`)
                break;
            case 10:
                $('#passStrengthBar').addClass('bg-danger')
                $('#passStrengthBar').removeClass('bg-success')
                $('#passStrengthBar').removeClass('bg-warning')
                $('#passwordInfo').html(`
                <div style="font-size: 11px !important" class="mt-2">
                    <h6 style="font-size: 11px !important" class="font-weight-bold"> Mot de passe faible </h6>
                    <p> Pour avoir un mot de passe fort </p>
                    <ul class="w-100 pl-4">
                        <li> Au moins 2 minuscules consécutifs </li>
                        <li> Au moins 2 majuscules consécutifs </li>
                        <li> Au moins 2 chiffres consécutifs </li>
                        <li> Au moins 1 caractère spécial </li>
                        <li> Mot de passe de 8 caractères ou plus </li>
                    </ul>
                </div>`)
                break;
            default:
                break;
        }

    }

    /**
     * Display password strength
     */
    const displayStrength = (password) => {
        var strongRegex = new RegExp("((?=.*[a-z]{2,})(?=.*[A-Z]{2,})(?=.*[0-9]{2,})(?=.*[!@#\$%\^&\*]))(?=.{8,})");
        var mediumRegex = new RegExp("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})");

        console.log(password);

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
            $('#passwordInfo').removeClass('d-none');
        } else {
            $('#passStrength').addClass('d-none');
            $('#passwordInfo').addClass("d-none");
        }
        displayStrength(chosenPass);
    });


    $('')

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