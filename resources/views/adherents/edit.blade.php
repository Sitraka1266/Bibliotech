@extends('layouts.app')
@section('title', 'Modifier un adhérent')
@section('content')
<h1 class="mb-4 text-2xl font-bold">Modifier {{ $adherent->nomComplet() }}</h1>
@include('adherents._form', ['action' => route('adherents.update', $adherent)])
@endsection