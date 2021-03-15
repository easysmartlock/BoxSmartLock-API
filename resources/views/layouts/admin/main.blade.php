@extends('layouts.main')


@section('body')
<nav class="navbar navbar-expand-md bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Espace Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="color: #fff">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </span>
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
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('easy_index') }}">Serrures</a>
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