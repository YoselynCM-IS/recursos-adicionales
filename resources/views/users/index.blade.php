@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif  
    <recursos-acceso-component :libro="{{$libro}}"></recursos-acceso-component>
@endsection
