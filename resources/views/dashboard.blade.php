@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('content')
<h1 class="mb-4 text-2xl font-bold">Tableau de bord</h1>

<div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    @php
    $cards = [
    ['Livres', $nbLivres, 'bg-blue-600', route('livres.index')],
    ['Adhérents', $nbAdherents, 'bg-emerald-600', route('adherents.index')],
    ['Emprunts en cours', $nbEnCours, 'bg-amber-500', route('emprunts.index')],
    ['Retards', $nbRetards, 'bg-red-600', route('emprunts.index')],
    ];
    @endphp
    @foreach ($cards as [$label, $value, $color, $url])
    <a href="{{ $url }}" class="rounded-lg {{ $color }} p-5 text-white shadow hover:opacity-90">
        <div class="text-4xl font-bold">{{ $value }}</div>
        <div class="mt-1 text-sm uppercase tracking-wide opacity-90">{{ $label }}</div>
    </a>
    @endforeach
</div>

<div class="mt-6 rounded-lg bg-white p-5 shadow">
    <h2 class="mb-3 text-lg font-semibold">Retards à relancer</h2>
    @forelse ($retards as $e)
    <div class="flex flex-wrap justify-between gap-2 border-b border-slate-100 py-2 last:border-0">
        <span><strong>{{ $e->livre->titre }}</strong> — {{ $e->adherent->nomComplet() }}</span>
        <span class="text-sm font-medium text-red-700">{{ $e->joursDeRetard() }} jour(s) de retard (prévu le
            {{ $e->date_retour_prevue->format('d/m/Y') }})</span>
    </div>
    @empty
    <p class="text-slate-500">Aucun retard <i class="fa-solid fa-party-horn"></i></p>
    @endforelse
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <a href="{{ route('emprunts.create') }}"
        class="rounded-md bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">+ Nouvel emprunt</a>
    <a href="{{ route('livres.create') }}"
        class="rounded-md bg-white px-4 py-2 font-semibold shadow hover:bg-slate-50">+ Ajouter un livre</a>
    <a href="{{ route('adherents.create') }}"
        class="rounded-md bg-white px-4 py-2 font-semibold shadow hover:bg-slate-50">+ Ajouter un adhérent</a>
</div>
@endsection