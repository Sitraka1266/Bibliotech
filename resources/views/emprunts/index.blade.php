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
            <a href="{{ route('emprunts.create') }}" class="rounded-md bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">+ Nouvel emprunt</a>
        </div>
    </div>
    <form method="GET" action="{{ route('emprunts.export') }}" class="mb-4 flex flex-wrap items-end gap-3 rounded-lg bg-white p-4 shadow">
        <div>
            <label for="statut" class="mb-1 block text-sm font-medium text-slate-700">À exporter</label>
            <select id="statut" name="statut" class="rounded-md border border-slate-300 px-3 py-2">
                <option value="en_cours" @selected(request('statut', 'en_cours') === 'en_cours')>Emprunts en cours</option>
                <option value="historique" @selected(request('statut') === 'historique')>Historique (rendus)</option>
                <option value="tous" @selected(request('statut') === 'tous')>Tous les emprunts</option>
            </select>
        </div>
        <div>
            <label for="periode" class="mb-1 block text-sm font-medium text-slate-700">Période (facultatif)</label>
            <input
                id="periode"
                name="periode"
                type="number"
                min="1"
                max="120"
                value="{{ request('periode') }}"
                placeholder="Toutes les dates"
                class="w-36 rounded-md border border-slate-300 px-3 py-2"
            >
            @error('periode')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="unite" class="mb-1 block text-sm font-medium text-slate-700">Unité</label>
            <select id="unite" name="unite" class="rounded-md border border-slate-300 px-3 py-2">
                <option value="semaines" @selected(request('unite', 'semaines') === 'semaines')>Semaines</option>
                <option value="mois" @selected(request('unite') === 'mois')>Mois</option>
            </select>
            @error('unite')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <button class="rounded-md bg-slate-800 px-4 py-2 font-semibold text-white hover:bg-slate-700">⬇ Exporter CSV</button>
    </form>
    @include('emprunts._table', ['emprunts' => $emprunts, 'afficherAdherent' => true])
@endsection
