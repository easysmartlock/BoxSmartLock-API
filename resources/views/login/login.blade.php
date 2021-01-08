@extends('layouts.main')

@section('body')
<div class="container h100">
    <main class="row h100 align-items-center d-flex justify-content-center text-center">
        <form class="col-9 col-sm-6" method="POST">
            @csrf
            <h1 class="h3 mb-3 fw-normal">
                Connexion
            </h1>
            <label for="inputEmail" class="visually-hidden">Email</label>
            <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" required autofocus>
            <label for="inputPassword" class="visually-hidden">Mot de passe</label>
            <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Mot de passe" required>
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
    </main>
</div>
@endsection