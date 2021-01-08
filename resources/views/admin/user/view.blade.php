@extends('layouts.admin.main')


@section('content')
<div class="row">
    <h3>
        Détail client
    </h3>
</div>
<div class="row my-4">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header">
                Informations
            </div>
            <div class="card-body">
              <h5 class="card-title">
                  {{ $user->prenom }} {{ $user->nom }}
              </h5>
              <h6 class="card-subtitle my-4 text-muted">
                <i class="lni lni-phone"></i> {{ $user->telephone }} <br/>
                <i class="lni lni-inbox"></i> {{ $user->email }}
              </h6>
              <p class="card-text">
                  &nbsp;
              </p>
              <a href="#" class="card-link btn btn-danger">
                  Supprimer
              </a>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header">
                Liste des boitiers
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($user->boxes as $box)
                    <li class="list-group-item">
                        {{  $box->telephone }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection


@section('js')
    <script>
        $(document).ready(function() {
            
        });
    </script>
@endsection