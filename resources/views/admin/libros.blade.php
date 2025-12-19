@extends('layouts.app')

@section('content')
    <libros-lista-component role="{{auth()->user()->role->role}}"></libros-lista-component>
@endsection