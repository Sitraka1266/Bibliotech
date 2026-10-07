@extends('layouts.app')
@section('title', 'Adhérents')
@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold">Adhérents</h1>
        <a href="{{ route('adherents.create') }}" class="rounded-md bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">+ Ajouter un adhérent</a>
    </div>
    <form method="GET" class="mb-4 flex gap-3 rounded-lg bg-white p-4 shadow">
        <input name="q" value="{{ request('q') }}" placeholder="Nom, prénom ou email…" class="flex-1 rounded-md border border-slate-300 px-3 py-2">
        <button class="rounded-md bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Rechercher</button>
    </form>
    <div class="overflow-x-auto rounded-lg bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="px-4 py-3">Photo</th><th class="px-4 py-3">Nom</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Téléphone</th>
                    <th class="px-4 py-3">Inscrit le</th><th class="px-4 py-3">Emprunts en cours</th><th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($adherents as $a)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">
                        @if ($a->photo_path)
                            <img src="{{ asset('storage/'.$a->photo_path) }}" alt="Photo de profil de {{ $a->nomComplet() }}" class="size-10 rounded-full object-cover">
                        @else
                            <span role="img" aria-label="Aucune photo de profil" class="flex size-10 items-center justify-center rounded-full bg-slate-200 text-slate-500">
                                <i class="fa-solid fa-user"></i>
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium"><a href="{{ route('adherents.show', $a) }}" class="text-blue-700 hover:underline">{{ $a->nomComplet() }}</a></td>
                    <td class="px-4 py-3">{{ $a->email }}</td>
                    <td class="px-4 py-3">{{ $a->telephone ?: '—' }}</td>
                    <td class="px-4 py-3">{{ $a->date_inscription->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $a->emprunts_en_cours_count }} / 3</td>
                    <td class="whitespace-nowrap px-4 py-3 text-right">
                        <a href="{{ route('adherents.show', $a) }}" class="text-slate-600 hover:underline">Fiche</a>
                        <a href="{{ route('adherents.edit', $a) }}" class="ml-3 text-blue-600 hover:underline">Modifier</a>
                        <form method="POST" action="{{ route('adherents.destroy', $a) }}" class="ml-3 inline"
                              onsubmit="return confirm('Supprimer cet adhérent ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">Aucun adhérent trouvé.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $adherents->links() }}</div>
@endsection
