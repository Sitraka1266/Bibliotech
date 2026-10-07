@extends('layouts.app')
@section('title', 'Livres')
@section('content')
<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-2xl font-bold">Catalogue des livres</h1>
    <a href="{{ route('livres.create') }}"
        class="rounded-md bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">+ Ajouter un livre</a>
</div>

<form method="GET" data-live-book-filters class="mb-4 flex flex-wrap items-end gap-3 rounded-lg bg-white p-4 shadow">
    <div class="min-w-48 flex-1">
        <label for="book-search" class="mb-1 block text-sm font-medium">Titre ou auteur</label>
        <input id="book-search" name="q" value="{{ request('q') }}" placeholder="Rechercher…"
            class="w-full rounded-md border border-slate-300 px-3 py-2">
    </div>
    <div>
        <label for="book-category" class="mb-1 block text-sm font-medium">Catégorie</label>
        <select id="book-category" name="categorie" class="rounded-md border border-slate-300 px-3 py-2">
            <option value="">Toutes</option>
            @foreach ($categories as $c)
            <option value="{{ $c }}" @selected(request('categorie')===$c)>{{ $c }}</option>
            @endforeach
        </select>
    </div>
    <label class="flex items-center gap-2 pb-2 text-sm">
        <input type="checkbox" name="disponibles" value="1" @checked(request()->boolean('disponibles'))> Disponibles
        uniquement
    </label>
    <button class="rounded-md bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Filtrer</button>
    <a href="{{ route('livres.index') }}" class="pb-2 text-sm text-slate-500 underline">Réinitialiser</a>
</form>

<div class="overflow-x-auto rounded-lg bg-white shadow">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-800 text-white">
            <tr>
                <th class="px-4 py-3">Titre</th>
                <th class="px-4 py-3">Auteur</th>
                <th class="px-4 py-3">ISBN</th>
                <th class="px-4 py-3">Catégorie</th>
                <th class="px-4 py-3">Année</th>
                <th class="px-4 py-3">Disponibles</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($livres as $livre)
            <tr class="border-t border-slate-100">
                <td class="px-4 py-3 font-medium">{{ $livre->titre }}</td>
                <td class="px-4 py-3">{{ $livre->auteur }}</td>
                <td class="px-4 py-3">{{ $livre->isbn }}</td>
                <td class="px-4 py-3">{{ $livre->categorie }}</td>
                <td class="px-3 py-3">{{ $livre->annee }}</td>
                <td class="px-4 py-3">
                    <span
                        class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $livre->quantite_disponible > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $livre->quantite_disponible }} / {{ $livre->quantite_totale }}
                    </span>
                </td>
                <td class="whitespace-nowrap px-4 py-3 text-right">
                    <a href="{{ route('livres.edit', $livre) }}"
                        class="text-blue-600 hover:underline btn btn-primary"><i
                            class="fa-regular fa-pen-to-square"></i></a>
                    <form method="POST" action="{{ route('livres.destroy', $livre) }}" class="ml-3 inline"
                        data-delete-confirm data-delete-item="{{ $livre->titre }}">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline btn btn-danger"><i
                                class="fa-solid fa-trash-can"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-slate-500">Aucun livre trouvé.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $livres->links() }}</div>
@endsection