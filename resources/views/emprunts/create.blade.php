@extends('layouts.app')
@section('title', 'Nouvel emprunt')
@section('content')
    <h1 class="mb-4 text-2xl font-bold">Enregistrer un emprunt</h1>
    <form method="POST" action="{{ route('emprunts.store') }}" class="max-w-xl space-y-4 rounded-lg bg-white p-6 shadow" novalidate>
        @csrf
        <div>
            <label for="livre_id" class="mb-1 block text-sm font-medium">Livre <span class="text-red-600">*</span></label>
            <select id="livre_id" name="livre_id" class="w-full rounded-md border px-3 py-2 {{ $errors->has('livre_id') ? 'border-red-500 bg-red-50' : 'border-slate-300' }}">
                <option value="">— Choisir un livre disponible —</option>
                @foreach ($livres as $l)
                    <option value="{{ $l->id }}" @selected(old('livre_id') == $l->id)>{{ $l->titre }} ({{ $l->quantite_disponible }} dispo.)</option>
                @endforeach
            </select>
            @error('livre_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="adherent_id" class="mb-1 block text-sm font-medium">Adhérent <span class="text-red-600">*</span></label>
            <select id="adherent_id" name="adherent_id" class="w-full rounded-md border px-3 py-2 {{ $errors->has('adherent_id') ? 'border-red-500 bg-red-50' : 'border-slate-300' }}">
                <option value="">— Choisir un adhérent —</option>
                @foreach ($adherents as $a)
                    <option value="{{ $a->id }}" @selected(old('adherent_id') == $a->id)>{{ $a->nomComplet() }}</option>
                @endforeach
            </select>
            @error('adherent_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <x-field name="duree" label="Durée (jours)" type="number" :value="$dureeDefaut" min="1" max="60" :required="false" />
        <div class="flex gap-3">
            <button class="rounded-md bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Enregistrer l'emprunt</button>
            <a href="{{ route('emprunts.index') }}" class="rounded-md px-4 py-2 text-slate-600 hover:bg-slate-100">Annuler</a>
        </div>
    </form>
@endsection
