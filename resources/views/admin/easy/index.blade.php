@extends('layouts.admin.main')


@section('content')
<div class="row">
    <h3>
        Liste des serrures
    </h3>
</div>
<div class="row">
    <div class="col-12 col-sm-6 col-md-5 col-lg-4">
        <a href="#" id="btn-easy-add" class="btn btn-success">
            Ajouter une nouvelle serrure
        </a>
    </div>
</div>
<div class="row my-4">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>
                        #
                    </th>
                    <th>
                        Nom
                    </th>
                    <th>
                        Mot de passe
                    </th>
                    <th>
                        Téléphone
                    </th>
                    <th>
                        Client
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($easies as $easy)
                    <tr>
                        <td>
                            {{ $easy->identifiant }}
                        </td>
                        <td>
                            {{ $easy->nom }}
                        </td>
                        <td>
                            {{ $easy->pass }}
                        </td>
                        <td>
                            {{ $easy->telephone }}
                        </td>
                        <td>
                            @if($easy->user)
                                <small>
                                    <a href="{{ route('user_view',['id' => $easy->user->id]) }}">
                                        {{ $easy->user->prenom }} {{ $easy->user->nom }} <br/>
                                        [ {{ $easy->user->email }} ]
                                    </a>
                                </small>
                            @else
                                <small>
                                    Aucun
                                </small>
                            @endif
                        </td>
                        <td>
                            <a data-id="{{ $easy->id }}" class="btn btn-primary btn-attach-client" title="Rattaché a un client" href="#">
                                <i class="lni lni-user"></i>
                            </a>                           
                            <a data-id="{{ $easy->id }}" title="Modifier mot de passe" class="btn-pass-easy-edit btn btn-primary" href="">
                                <i class="lni lni-money-protection"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- add modal -->
<div id="add-modal" class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ajouter une nouvelle serrure</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>
            <form id="form-add-easy" method="POST">
                @csrf
                <div class="form-floating mb-3">
                    <input type="texte" class="form-control" name="identifiant" required>
                    <label>Identifiant</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="texte" class="form-control" name="hebergement"/>
                    <label>Hebergement</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="texte" class="form-control" name="nom" required>
                    <label>Nom de la serrure</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="texte" class="form-control" name="telephone">
                    <label>Téléphone de la serrure</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="texte" class="form-control" name="pass">
                    <label>Pass de la serrure</label>
                </div>
                <div style="display: none">
                    <button id="btn-easy-ok"></button>
                </div>
            </form>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button id="btn-easy-save" type="button" class="btn btn-primary">Sauvegarder</button>
        </div>
      </div>
    </div>
</div>

<!-- attach modal -->
<div id="attach-modal" class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Attacher une serrure</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>
            <form id="form-attach-box" action="{{ route('easy_attach') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="id" />
                <div class="form-floating mb-3">
                    <select name="user_id" id="select-user" class="form-select"></select>
                    <label>Choisissez un utilisateur</label>
                </div>
                <div style="display: none">
                    <button id="btn-easy-attach"></button>
                </div>
            </form>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button id="btn-easy-attach-save" type="button" class="btn btn-primary">Sauvegarder</button>
        </div>
      </div>
    </div>
</div>

<!-- password modal -->
<div id="pass-easy-modal" class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modifier mot de passe de la serrure</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>
            <form id="form-pass-box" action="{{ route('easy_pass') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="passid" />
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="pass" required>
                    <label>Mot de passe</label>
                </div>
                <div style="display: none">
                    <button id="btn-easy-pass"></button>
                </div>
            </form>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button id="btn-easy-pass-save" type="button" class="btn btn-primary">Sauvegarder</button>
        </div>
      </div>
    </div>
</div>


@endsection


@section('js')
    <script>
        $(document).ready(function() {

            $.get('{{ route('user_index_json') }}', function(json){
                json.forEach(element => {
                    $('#select-user').append('<option value="'+ element.id +'">'+ element.prenom +' '+ element.nom +' </option>');
                });
            },'json');

            $('#btn-easy-add').click(function(e) {
                e.preventDefault();
                $('#add-modal').modal("show");
            });

            $('#btn-easy-attach-save').click(function(e) {
                $('#btn-easy-attach').click();
            });

            $('#btn-easy-save').click(function(e) {
                $('#btn-easy-ok').click();
            });

            $('#btn-easy-pass-save').click(function(e) {
                $('#btn-easy-pass').click();
            });

            $('.btn-attach-client').click(function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $('#attach-modal').modal("show");
                $("#id").val(id);

            });


            $('.btn-pass-easy-edit').click(function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $('#pass-easy-modal').modal("show");
                $("#passid").val(id);
            });
        });
    </script>
@endsection