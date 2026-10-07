@extends('layouts.app')
@section('title', 'Nouvel emprunt')
@section('content')
    @if ($errors->has('emprunt'))
        <dialog
            data-loan-error-dialog
            aria-labelledby="loan-error-title"
            class="w-[min(28rem,calc(100%-2rem))] rounded-2xl border border-slate-200 bg-white p-0 text-slate-800 shadow-2xl backdrop:bg-slate-950/60"
        >
            <div class="p-6 sm:p-7">
                <div class="flex items-start gap-4">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-amber-100 text-lg text-amber-700">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <h2 id="loan-error-title" class="text-lg font-bold text-slate-900">Emprunt impossible</h2>
                        <div class="mt-2 space-y-2 text-sm leading-6 text-slate-600">
                            @foreach ($errors->get('emprunt') as $message)
                                <p>{{ $message }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button
                        type="button"
                        data-loan-error-close
                        class="rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        Compris
                    </button>
                </div>
            </div>
        </dialog>
    @endif
    <div class="mx-auto max-w-3xl">
        <div class="mb-6 flex items-center gap-4">
            <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-xl text-emerald-700">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
            </span>
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Emprunts</p>
                <h1 class="text-2xl font-bold text-slate-900">Enregistrer un emprunt</h1>
                <p class="mt-1 text-sm text-slate-500">Sélectionnez l’ouvrage et l’adhérent concernés.</p>
            </div>
        </div>
        <form method="POST" action="{{ route('emprunts.store') }}" class="space-y-7 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8" novalidate>
            @csrf
            <section class="space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="font-semibold text-slate-900">Détails de l’emprunt</h2>
                    <p class="mt-1 text-sm text-slate-500">Tous les champs de sélection sont obligatoires.</p>
                </div>
                <div>
                    <label for="livre_id" class="mb-2 block text-sm font-medium text-slate-700">Livre <span class="text-red-600">*</span></label>
                    <div
                        data-search-select
                        data-search-url="{{ route('emprunts.recherche.livres') }}"
                        data-search-empty="Aucun livre disponible trouvé."
                        class="relative"
                    >
                        <input
                            type="hidden"
                            name="livre_id"
                            value="{{ $livreSelectionne?->id }}"
                            data-search-value
                        >
                        <input
                            id="livre_id"
                            type="search"
                            role="combobox"
                            aria-autocomplete="list"
                            aria-expanded="false"
                            aria-controls="livre-options"
                            aria-describedby="livre-search-help"
                            autocomplete="off"
                            required
                            placeholder="Rechercher par titre, auteur ou ISBN…"
                            value="{{ $livreSelectionne ? $livreSelectionne->titre.' — '.$livreSelectionne->auteur : '' }}"
                            data-search-input
                            class="w-full rounded-xl border bg-white px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 {{ $errors->has('livre_id') ? 'border-red-500 bg-red-50' : 'border-slate-300' }}"
                        >
                        <ul id="livre-options" role="listbox" data-search-results class="absolute z-10 mt-1 hidden max-h-60 w-full overflow-y-auto rounded-xl border border-slate-200 bg-white py-1 shadow-lg"></ul>
                        <p id="livre-search-help" data-search-status aria-live="polite" class="mt-1 text-xs text-slate-500"></p>
                    </div>
                    @error('livre_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="adherent_id" class="mb-2 block text-sm font-medium text-slate-700">Adhérent <span class="text-red-600">*</span></label>
                    <div
                        data-search-select
                        data-search-url="{{ route('emprunts.recherche.adherents') }}"
                        data-search-empty="Aucun adhérent trouvé."
                        class="relative"
                    >
                        <input
                            type="hidden"
                            name="adherent_id"
                            value="{{ $adherentSelectionne?->id }}"
                            data-search-value
                        >
                        <input
                            id="adherent_id"
                            type="search"
                            role="combobox"
                            aria-autocomplete="list"
                            aria-expanded="false"
                            aria-controls="adherent-options"
                            aria-describedby="adherent-search-help"
                            autocomplete="off"
                            required
                            placeholder="Rechercher par nom, email ou téléphone…"
                            value="{{ $adherentSelectionne ? $adherentSelectionne->nomComplet().' — '.$adherentSelectionne->email : '' }}"
                            data-search-input
                            class="w-full rounded-xl border bg-white px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 {{ $errors->has('adherent_id') ? 'border-red-500 bg-red-50' : 'border-slate-300' }}"
                        >
                        <ul id="adherent-options" role="listbox" data-search-results class="absolute z-10 mt-1 hidden max-h-60 w-full overflow-y-auto rounded-xl border border-slate-200 bg-white py-1 shadow-lg"></ul>
                        <p id="adherent-search-help" data-search-status aria-live="polite" class="mt-1 text-xs text-slate-500"></p>
                    </div>
                    @error('adherent_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <x-field name="duree" label="Durée (jours)" type="number" :value="$dureeDefaut" min="1" max="60" :required="false" />
            </section>
            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('emprunts.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-2.5 font-semibold text-slate-700 transition hover:bg-slate-50">Annuler</a>
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <i class="fa-solid fa-check"></i>
                    Enregistrer l’emprunt
                </button>
            </div>
        </form>
    </div>
@endsection
