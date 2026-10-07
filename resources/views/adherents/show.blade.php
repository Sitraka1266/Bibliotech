@extends('layouts.app')
@section('title', $adherent->nomComplet())
@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-4">
            @if ($adherent->photo_path)
                <img src="{{ asset('storage/'.$adherent->photo_path) }}" alt="Photo de profil de {{ $adherent->nomComplet() }}" class="size-16 rounded-full object-cover">
            @else
                <span role="img" aria-label="Aucune photo de profil" class="flex size-16 items-center justify-center rounded-full bg-slate-200 text-slate-500">
                    <i class="fa-solid fa-user text-xl"></i>
                </span>
            @endif
            <h1 class="text-2xl font-bold">{{ $adherent->nomComplet() }}</h1>
        </div>
        <a href="{{ route('adherents.index') }}" class="text-sm text-slate-600 underline">← Retour à la liste</a>
    </div>
    <div class="mb-6 rounded-lg bg-white p-5 shadow">
        <dl class="grid gap-3 text-sm sm:grid-cols-3">
            <div><dt class="text-slate-500">Email</dt><dd class="font-medium">{{ $adherent->email }}</dd></div>
            <div><dt class="text-slate-500">Téléphone</dt><dd class="font-medium">{{ $adherent->telephone ?: '—' }}</dd></div>
            <div><dt class="text-slate-500">Inscrit le</dt><dd class="font-medium">{{ $adherent->date_inscription->format('d/m/Y') }}</dd></div>
        </dl>
    </div>
    <h2 class="mb-2 text-lg font-semibold">Historique des emprunts</h2>
    @include('emprunts._table', ['emprunts' => $emprunts, 'afficherAdherent' => false])
@endsection
