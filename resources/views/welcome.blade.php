@extends('layout.app')

@section('content')
    <div class="container centerContainer">
        <div id="flashMessage" class="w-50 mx-auto"></div>

        <form method="POST" id="login" class="card mx-auto" style="width: 30rem;">
            <div class="card-body">
                <h5 class="text-center font-weight-bold">Connexion</h5>
                <div class="mt-5 mb-4">
                    <input id="email" type="email" placeholder="email" class="form-control  my-2">
                    <input id="password" type="password" placeholder="mot de passe" class="form-control  my-2">
                </div>
                <div class="d-flex w-100 justify-content-end">
                    <a href="{{ route('inscription') }}" class="btn btn-secondary mr-1">S'inscrire</a>
                    <button type="submit" class="btn btn-primary"> Se connecter </button>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/script.js')}}"> </script>
@endsection