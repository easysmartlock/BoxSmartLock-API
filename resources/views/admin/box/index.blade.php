@extends('layouts.admin.main')


@section('content')
<div class="row">
    <h3>
        Liste des box
    </h3>
</div>
<div class="row">
    <div class="col-12 col-sm-6 col-md-5 col-lg-4">
        <a href="#" id="btn-box-add" class="btn btn-success">
            Ajouter une nouvelle box
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
                @foreach($boxes as $box)
                    <tr>
                        <td>
                            {{ $box->id }}
                        </td>
                        <td>
                            {{ $box->nom }}
                        </td>
                        <td>
                            {{ $box->pass }}
                        </td>
                        <td>
                            {{ $box->telephone }}
                        </td>
                        <td>
                            @if($box->user)
                                <small>
                                    <a href="{{ route('user_view',['id' => $box->user->id]) }}">
                                        {{ $box->user->prenom }} {{ $box->user->nom }} <br/>
                                        [ {{ $box->user->email }} ]
                                    </a>
                                </small>
                            @else
                                <small>
                                    Aucun
                                </small>
                            @endif
                        </td>
                        <td>
                           @if(!$box->user)
                                <a data-id="{{ $box->id }}" class="btn btn-primary btn-attach-client" title="Rattaché a un client" href="#">
                                    <i class="lni lni-user"></i>
                                </a>
                           @endif
                           <a data-id="{{ $box->id }}" title="Modifier mot de passe" class="btn-pass-box-edit btn btn-primary" href="">
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
          <h5 class="modal-title">Ajouter une nouvelle box</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>
            <form id="form-add-box" method="POST">
                @csrf
                <div class="form-floating mb-3">
                    <input type="texte" class="form-control" name="nom" required>
                    <label>Nom de la box</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="texte" class="form-control" name="hebergement"/>
                    <label>Hebergement</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="texte" class="form-control" name="telephone">
                    <label>Téléphone de la box</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="texte" class="form-control" name="pass">
                    <label>Pass de la box</label>
                </div>
                <div style="display: none">
                    <button id="btn-box-ok"></button>
                </div>
            </form>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button id="btn-box-save" type="button" class="btn btn-primary">Sauvegarder</button>
        </div>
      </div>
    </div>
</div>

<!-- attach modal -->
<div id="attach-modal" class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Attacher une box</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>
            <form id="form-attach-box" action="{{ route('box_attach') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="id" />
                <div class="form-floating mb-3">
                    <select name="user_id" id="select-user" class="form-select"></select>
                    <label>Choisissez un utilisateur</label>
                </div>
                <div style="display: none">
                    <button id="btn-box-attach"></button>
                </div>
            </form>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button id="btn-box-attach-save" type="button" class="btn btn-primary">Sauvegarder</button>
        </div>
      </div>
    </div>
</div>

<!-- password modal -->
<div id="pass-box-modal" class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modifier mot de passe de la box</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>
            <form id="form-pass-box" action="{{ route('box_pass') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="passid" />
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="pass" required>
                    <label>Mot de passe</label>
                </div>
                <div style="display: none">
                    <button id="btn-box-pass"></button>
                </div>
            </form>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button id="btn-box-pass-save" type="button" class="btn btn-primary">Sauvegarder</button>
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

            $('#btn-box-add').click(function(e) {
                e.preventDefault();
                $('#add-modal').modal("show");
            });

            $('#btn-box-attach-save').click(function(e) {
                $('#btn-box-attach').click();
            });

            $('#btn-box-save').click(function(e) {
                $('#btn-box-ok').click();
            });

            $('#btn-box-pass-save').click(function(e) {
                $('#btn-box-pass').click();
            });

            $('.btn-attach-client').click(function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $('#attach-modal').modal("show");
                $("#id").val(id);

            });


            $('.btn-pass-box-edit').click(function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $('#pass-box-modal').modal("show");
                $("#passid").val(id);
            });
        });
    </script>
@endsection