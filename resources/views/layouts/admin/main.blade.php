@extends('layouts.main')


@section('body')
<nav class="navbar navbar-expand-md bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Espace Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item active">
                    <a class="nav-link" aria-current="page" href="/">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user_index') }}">Clients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('box_index') }}">Boitiers</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
  
<main class="container">
    <div class="bg-light p-5 rounded">
        @if(session('message'))
            <div class="row my-3">
                <div class="alert alert-danger">
                    {{ session('message') }}
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="row my-3">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @yield('content')
    </div>
</main>

@endsection