<form method="POST" action="{{ $action }}" class="space-y-7 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8" novalidate>
    @csrf
    @if ($livre->exists) @method('PUT') @endif
    <section class="space-y-4">
        <div class="border-b border-slate-100 pb-3">
            <h2 class="font-semibold text-slate-900">Informations du livre</h2>
            <p class="mt-1 text-sm text-slate-500">Les champs marqués d’un astérisque sont obligatoires.</p>
        </div>
        <div class="grid gap-5 sm:grid-cols-2">
            <x-field name="titre" label="Titre" :value="$livre->titre" />
            <x-field name="auteur" label="Auteur" :value="$livre->auteur" />
            <x-field name="isbn" label="ISBN" :value="$livre->isbn" />
            <x-field name="categorie" label="Catégorie" :value="$livre->categorie" />
            <x-field name="annee" label="Année" type="number" :value="$livre->annee" min="1000" :max="date('Y')" />
            <x-field name="quantite_totale" label="Quantité totale" type="number" :value="$livre->quantite_totale" min="0" />
        </div>
    </section>
    @if ($livre->exists)
        <p class="rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-900">
            Exemplaires actuellement empruntés : <span class="font-semibold">{{ $livre->nbEmpruntsEnCours() }}</span>
        </p>
    @endif
    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
        <a href="{{ route('livres.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-2.5 font-semibold text-slate-700 transition hover:bg-slate-50">Annuler</a>
        <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="fa-solid fa-check"></i>
            Enregistrer le livre
        </button>
    </div>
</form>
