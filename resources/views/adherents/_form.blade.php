<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-7 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8" novalidate>
    @csrf
    @if ($adherent->exists) @method('PUT') @endif
    <section class="space-y-4">
        <div class="border-b border-slate-100 pb-3">
            <h2 class="font-semibold text-slate-900">Informations personnelles</h2>
            <p class="mt-1 text-sm text-slate-500">Les champs marqués d’un astérisque sont obligatoires.</p>
        </div>
        <div class="grid gap-5 sm:grid-cols-2">
            <x-field name="nom" label="Nom" :value="$adherent->nom" />
            <x-field name="prenom" label="Prénom" :value="$adherent->prenom" />
            <x-field name="email" label="Email" type="email" :value="$adherent->email" />
            <x-field name="telephone" label="Téléphone" :value="$adherent->telephone" :required="false" />
            <x-field name="date_inscription" label="Date d'inscription" type="date" :value="$adherent->date_inscription?->format('Y-m-d')" />
        </div>
    </section>
    <section class="space-y-3">
        <div>
            <h2 class="font-semibold text-slate-900">Photo de profil <span class="font-normal text-slate-400">(facultatif)</span></h2>
            <p class="mt-1 text-sm text-slate-500">Ajoutez une photo pour reconnaître plus facilement l’adhérent.</p>
        </div>
        <div class="flex flex-col gap-4 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 sm:flex-row sm:items-center">
            @if ($adherent->photo_path)
                <img
                    src="{{ asset('storage/'.$adherent->photo_path) }}"
                    alt="Photo de profil de {{ $adherent->nomComplet() }}"
                    class="size-16 rounded-full object-cover"
                >
            @else
                <span class="flex size-16 shrink-0 items-center justify-center rounded-full bg-white text-2xl text-slate-400 ring-1 ring-slate-200">
                    <i class="fa-regular fa-image"></i>
                </span>
            @endif
            <div class="min-w-0 flex-1 space-y-2">
                <input
                    id="photo"
                    name="photo"
                    type="file"
                    accept="image/jpeg,image/png,image/webp"
                    class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-100 file:px-3 file:py-2 file:font-semibold file:text-blue-700 hover:file:bg-blue-200"
                >
                <p class="text-xs text-slate-500">JPEG, PNG ou WebP — 10 Mo maximum.</p>
                @error('photo')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>
    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
        <a href="{{ route('adherents.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-2.5 font-semibold text-slate-700 transition hover:bg-slate-50">Annuler</a>
        <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <i class="fa-solid fa-check"></i>
            Enregistrer l’adhérent
        </button>
    </div>
</form>
