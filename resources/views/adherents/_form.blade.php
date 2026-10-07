<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-4 rounded-lg bg-white p-6 shadow" novalidate>
    @csrf
    @if ($adherent->exists) @method('PUT') @endif
    <div class="grid gap-4 sm:grid-cols-2">
        <x-field name="nom" label="Nom" :value="$adherent->nom" />
        <x-field name="prenom" label="Prénom" :value="$adherent->prenom" />
        <x-field name="email" label="Email" type="email" :value="$adherent->email" />
        <x-field name="telephone" label="Téléphone" :value="$adherent->telephone" :required="false" />
        <x-field name="date_inscription" label="Date d'inscription" type="date" :value="$adherent->date_inscription?->format('Y-m-d')" />
    </div>
    <div class="space-y-2">
        <label for="photo" class="block text-sm font-medium text-slate-700">Photo de profil</label>
        @if ($adherent->photo_path)
            <img
                src="{{ asset('storage/'.$adherent->photo_path) }}"
                alt="Photo de profil de {{ $adherent->nomComplet() }}"
                class="size-20 rounded-full object-cover"
            >
        @endif
        <input
            id="photo"
            name="photo"
            type="file"
            accept="image/jpeg,image/png,image/webp"
            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm file:mr-3 file:rounded file:border-0 file:bg-slate-100 file:px-3 file:py-1.5"
        >
        <p class="text-xs text-slate-500">JPEG, PNG ou WebP — 10 Mo maximum.</p>
        @error('photo')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <div class="flex gap-3">
        <button class="rounded-md bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Enregistrer</button>
        <a href="{{ route('adherents.index') }}" class="rounded-md px-4 py-2 text-slate-600 hover:bg-slate-100">Annuler</a>
    </div>
</form>
