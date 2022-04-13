@extends('layouts.admin.main')


@section('content')
<div class="row">
    <h3>
        Liste des clients
    </h3>
</div>
<div class="row">
    <div class="col-12 col-sm-6 col-md-5 col-lg-4">
        <a href="#" id="btn-user-add" class="btn btn-success">
            Ajouter un nouveau client
        </a>
    </div>
</div>
<div class="row my-4">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>
                        Prénom
                    </th>
                    <th>
                        Nom
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Téléphone
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            {{ $user->prenom }}
                        </td>
                        <td>
                            {{ $user->nom }}
                        </td>
                        <td>
                            <a href="{{ route('user_view', ['id' => $user->id]) }}">
                                {{ $user->email }}
                            </a>
                        </td>
                        <td>
                            {{ $user->telephone }}
                        </td>
                        <td>
                            <a title="Supprimer" class="btn btn-warning" href="{{ route('user_delete',['id' => $user->id]) }}">
                                <i class="lni lni-trash"></i>
                            </a>
                            <a title="Supprimer" class="btn btn-info" href="{{ route('user_edit',['id' => $user->id]) }}">
                                <i class="lni lni-pencil"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div id="add-modal" class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ajouter un nouveau client</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>
            <form id="form-add-user" method="POST">
                @csrf
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" name="email" required>
                    <label>Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="texte" class="form-control" name="prenom">
                    <label>Prénom</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="texte" class="form-control" name="nom">
                    <label>Nom</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="texte" class="form-control" name="telephone" required>
                    <label>Téléphone</label>
                </div>
                <div style="display: none">
                    <button id="btn-ok"></button>
                </div>
            </form>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button id="btn-user-save" type="button" class="btn btn-primary">Sauvegarder</button>
        </div>
      </div>
    </div>
</div>
@endsection


@section('js')
    <script>
        $(document).ready(function() {
            $('#btn-user-add').click(function(e) {
                e.preventDefault();
                $('#add-modal').modal("show");
            });

            $('#btn-user-save').click(function(e) {
                $('#btn-ok').click();
            });
        });
    </script>
@endsection