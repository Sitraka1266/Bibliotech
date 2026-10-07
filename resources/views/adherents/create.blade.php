@extends('layouts.app')
@section('title', 'Ajouter un adhérent')
@section('content')
    <h1 class="mb-4 text-2xl font-bold">Ajouter un adhérent</h1>
    @include('adherents._form', ['action' => route('adherents.store')])
@endsection
