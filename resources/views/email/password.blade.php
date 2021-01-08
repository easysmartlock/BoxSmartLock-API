Bonjour {{ $user->prenom }} {{ $user->nom }},<br/>
Pour réinitialiser votre mot de passe, veuillez cliquer <a href="{{ route('password',['token_password' => $user->token_password]) }}">ici</a>.
