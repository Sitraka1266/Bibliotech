@extends('layouts.app')
@section('title', 'Modifier un livre')
@section('content')
    <h1 class="mb-4 text-2xl font-bold">Modifier « {{ $livre->titre }} »</h1>
    @include('livres._form', ['action' => route('livres.update', $livre)])
@endsection
