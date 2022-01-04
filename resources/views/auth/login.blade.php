@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/style-login.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-5">
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
                                <label for="codigo" class="col-md-4 col-form-label text-md-right" >{{ __('Código') }}</label>
                                <div class="col-md-6">
                                    <input id="codigo" type="text" 
                                        class="form-control @error('codigo') is-invalid @enderror" 
                                        name="codigo" value="{{ old('codigo') }}" required 
                                        autocomplete="codigo" autofocus>
                                    @error('codigo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" id="password" name="password" value="CODPRUEBA">
                            <div class="form-group row" >
                                <div class="col-md-8 offset-md-4" >
                                    <label>
                                        {{ __('Ingresa tu código para poder consultar los recursos') }}
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
