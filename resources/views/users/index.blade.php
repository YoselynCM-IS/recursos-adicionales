@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif  
    @if(auth()->user()->name == NULL)
        <div class="row justify-content-center">
            <user-save-inf-component class="col-md-6" :form="{{$form}}" :edit="false"></user-save-inf-component>
        </div>
    @else
        <recursos-acceso-component :libro="{{$libro}}"></recursos-acceso-component>
    @endif
@endsection
