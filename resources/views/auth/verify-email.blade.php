@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">¡Gracias por registrarte!</h5>
            <p class="card-text">
                Antes de empezar, por favor verifica tu correo electrónico haciendo clic en el enlace que te enviamos. Si no has recibido el correo, con gusto te enviaremos otro.
            </p>
        </div>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mt-4" role="alert">
            Se ha enviado un nuevo enlace de verificación al correo electrónico que registraste.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <button type="submit" class="btn btn-dark">Reenviar correo de verificación</button>
            </div>
        </form>
    </div>
@endsection

