@extends('layouts.admin.main')

@section('content')
<div class="row">
    <h3>
        Historique
    </h3>
</div>
<div class="row">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    Date
                </th>
                <th>
                    Action
                </th>
                <th>
                    Paramètre
                </th>
                <th>
                    Utilisateur
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($historiques as $histo)
                <tr>
                    <td>
                        {{ date('d/M/Y H:i',strtotime($histo->created_at)) }}
                    </td>
                    <td>
                        @if($histo->action == \App\Models\Historique::ajoutTel)
                            Ajout de nouveau téléphone
                        @elseif($histo->action == \App\Models\Historique::suppressionTel)
                            Suppression téléphone
                        @elseif($histo->action == \App\Models\Historique::access)
                            Modification des accèss
                        @elseif($histo->action == \App\Models\Historique::duration)
                            Modification durée ouverture
                        @elseif($histo->action == \App\Models\Historique::listeTel)
                            Demande liste téléphone
                        @elseif($histo->action == \App\Models\Historique::modifPass)
                            Modification mot de passe
                        @endif

                        @if($histo->model == \App\Models\Historique::modelBox)
                            sur la Box
                        @else
                            sur la Serrure
                        @endif
                    </td>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        @if($histo->user)
                            {{ $histo->user->prenom }} {{ $histo->user->nom }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">
        {{ $historiques->links() }}
    </div>
</div>
@endsection