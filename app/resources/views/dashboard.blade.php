@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Bienvenido, {{ auth()->user()->persona->nombres }}</h1>
    <p>Rol: {{ auth()->user()->role->nombre }}</p>
@endsection