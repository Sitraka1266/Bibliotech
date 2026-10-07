<form method="POST" action="{{ $action }}" class="space-y-4 rounded-lg bg-white p-6 shadow" novalidate>
    @csrf
    @if ($livre->exists) @method('PUT') @endif
    <div class="grid gap-4 sm:grid-cols-2">
        <x-field name="titre" label="Titre" :value="$livre->titre" />
        <x-field name="auteur" label="Auteur" :value="$livre->auteur" />
        <x-field name="isbn" label="ISBN" :value="$livre->isbn" />
        <x-field name="categorie" label="Catégorie" :value="$livre->categorie" />
        <x-field name="annee" label="Année" type="number" :value="$livre->annee" min="1000" :max="date('Y')" />
        <x-field name="quantite_totale" label="Quantité totale" type="number" :value="$livre->quantite_totale" min="0" />
    </div>
    @if ($livre->exists)
        <p class="text-sm text-slate-500">Exemplaires actuellement empruntés : {{ $livre->nbEmpruntsEnCours() }}</p>
    @endif
    <div class="flex gap-3">
        <button class="rounded-md bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Enregistrer</button>
        <a href="{{ route('livres.index') }}" class="rounded-md px-4 py-2 text-slate-600 hover:bg-slate-100">Annuler</a>
    </div>
</form>
