@extends('layout.app')

@section('content')
    <div class="container centerContainer">
        <div id="flashMessage" class="w-50 mx-auto"></div>

        <form method="POST" id="inscription" class="card mx-auto" style="width: 30rem;">
            <div class="card-body">
                <h5 class="text-center font-weight-bold">Inscription</h5>
                <div class="mt-5 mb-4">
                    <input id="email" type="email" placeholder="Saisir votre adresse e-mail" class="form-control  my-2">
                    <input id="password" type="password" placeholder="Saisir votre mot de passe" class="form-control  my-2">
                    <input id="passwordConfirm" type="password" placeholder="Confirmer votre mot de passe" class="form-control  my-2">
                    <div id="passStrength" class="progress d-none">
                        <div id="passStrengthBar" class="progress-bar bg-success" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="passwordInfo"></div>
                </div>
                <div class="d-flex w-100 justify-content-end">
                    <a href="{{ route('login') }}" class="btn btn-secondary mr-1">Retour</a>
                    <button type="submit" class="btn btn-primary"> Inscription </button>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/inscription.js')}}"> </script>
@endsection