@extends('layouts.app')
@section('title', 'Emprunts')
@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold">{{ $tous ? 'Tous les emprunts' : 'Emprunts en cours' }}</h1>
        <div class="flex flex-wrap gap-2">
            @if ($tous)
                <a href="{{ route('emprunts.index') }}" class="rounded-md bg-white px-4 py-2 shadow hover:bg-slate-50">Voir les emprunts en cours</a>
            @else
                <a href="{{ route('emprunts.index', ['tous' => 1]) }}" class="rounded-md bg-white px-4 py-2 shadow hover:bg-slate-50">Voir tout l'historique</a>
            @endif
            <a href="{{ route('emprunts.export') }}" class="rounded-md bg-white px-4 py-2 shadow hover:bg-slate-50">⬇ Export CSV</a>
            <a href="{{ route('emprunts.create') }}" class="rounded-md bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">+ Nouvel emprunt</a>
        </div>
    </div>
    @include('emprunts._table', ['emprunts' => $emprunts, 'afficherAdherent' => true])
@endsection
