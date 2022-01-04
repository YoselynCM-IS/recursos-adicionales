@extends('layouts.app')

@section('content')
    <b-jumbotron bg-variant="light" text-variant="black" border-variant="danger"
        header="{{$user->acceso->libro->libro}}" lead="{{$situacion}}">
    </b-jumbotron>
@endsection