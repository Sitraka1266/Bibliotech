@extends('layouts.app')
@section('title', 'Ajouter un livre')
@section('content')
    <h1 class="mb-4 text-2xl font-bold">Ajouter un livre</h1>
    @include('livres._form', ['action' => route('livres.store')])
@endsection
