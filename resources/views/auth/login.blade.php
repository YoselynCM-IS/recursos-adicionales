@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/style-login.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-5">
        @if (session('status'))
            <div class="alert alert-success mb-4" role="alert">
                {{ session('status') }}
            </div>
        @endif  
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" id="clavehead">
                        <p><center>{{ __('Recursos adicionales') }}</center><p>
                    </div>

                    <div class="card-body" id="contenido">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right" ><b>{{ __('Correo electrónico') }}</b></label>
                                <div class="col-md-6">
                                    <input id="email" type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        name="email" value="{{ old('email') }}" required 
                                        autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="codigo" class="col-md-4 col-form-label text-md-right" ><b>{{ __('Código') }}</b></label>
                                <div class="col-md-6">
                                    <input id="password" type="text" 
                                        class="form-control @error('password') is-invalid @enderror" 
                                        name="password" value="{{ old('password') }}" required 
                                        autocomplete="password" autofocus>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row" >
                                <div class="col-md-8 offset-md-4" >
                                    <label>
                                        {{ __('Ingresa el correo electrónico que registraste y tu código para poder consultar los recursos.') }}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" id="btnacces">
                                        {{ __('Acceder') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
