@extends('layouts.admin.main')


@section('content')
<div class="row">
    <h3>
        Modifié client
    </h3>
</div>
<div class="row my-4">
	<div class="col-sm-6">
		<form method="POST">
			@csrf
			<div class="form-floating mb-3">				
				<input type="text" value="{{ $user->telephone }}" name="telephone" class="form-control" />
				<label>
					Téléphone
				</label>
			</div>
			<div class="form-floating mb-3">
				<input type="email" value="{{ $user->email }}" name="email" class="form-control" />
				<label>
					Email
				</label>
			</div>
			<div class="form-floating mb-3">				
				<input type="text" name="nom" value="{{ $user->nom }}" class="form-control" />
				<label>
					Nom
				</label>
			</div>
			<div class="form-floating mb-3">				
				<input type="text" name="prenom" value="{{ $user->prenom }}" class="form-control" />
				<label>
					Prénom
				</label>
			</div>
			<button class="btn btn-success" type="submit">
				Valider
			</button>
		</form>
	</div>
	<div class="col-sm-6">
		<h5>
			Boitiers
		</h5>
		<ul>
			@foreach($user->boxes as $box)
				<li>
					{{ $box->nom }} ( {{ $box->telephone }} )  
				</li>
			@endforeach
		</ul>
		<h5>
			Serrures
		</h5>
		<ul>
			@foreach($user->easies as $easy)
				<li>
					{{ $easy->nom }} ( {{ $easy->telephone }} )  
				</li>
			@endforeach
		</ul>
	</div>
</div>
@endsection