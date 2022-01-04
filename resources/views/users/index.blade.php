@extends('layouts.app')

@section('content')
    @if(auth()->user()->name == NULL)
        <div class="row justify-content-center">
            <user-save-inf-component class="col-md-6" :form="{{$form}}" :edit="false"></user-save-inf-component>
        </div>
    @else
        <recursos-acceso-component :libro="{{$libro}}"></recursos-acceso-component>
    @endif
@endsection
