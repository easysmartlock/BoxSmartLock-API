@extends('layouts.main')

@section('body')
<div class="container h100">
    <main class="row h100 align-items-center d-flex justify-content-center text-center">
        @if($user)
        <form class="col-9 col-sm-6" method="POST">
            @csrf
            <h1 class="h3 mb-3 fw-normal">
                Modifier votre mot de passe
            </h1>
            <div class="form-floating mb-3">
                <input type="password" id="inputEmail" name="password" class="form-control" required autofocus>
                <label for="inputEmail">Mot de passe</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" id="inputPassword" name="confpassword" class="form-control" required>
                <label for="inputPassword">Confirmez Mot de passe</label>
            </div>
            <div class="checkbox mb-3">
                <label>
                    &nbsp;
                </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">
                VALIDER
            </button>
            <p class="mt-5 mb-3 text-muted">&copy; 2020</p>
        </form>
        @else 
            <h4>
                Votre lien est invalide
            </h4>
        @endif
    </main>
</div>
@endsection