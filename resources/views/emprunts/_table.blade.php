<div class="overflow-x-auto rounded-lg bg-white shadow">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-800 text-white">
            <tr>
                <th class="px-4 py-3">Livre</th>
                @if ($afficherAdherent)<th class="px-4 py-3">Adhérent</th>@endif
                <th class="px-4 py-3">Emprunté le</th><th class="px-4 py-3">Retour prévu</th>
                <th class="px-4 py-3">Statut</th>
                @if ($afficherAdherent)<th class="px-4 py-3 text-right">Action</th>@endif
            </tr>
        </thead>
        <tbody>
        @forelse ($emprunts as $e)
            <tr class="border-t border-slate-100 {{ $e->estEnRetard() ? 'bg-red-50 text-red-900' : '' }}">
                <td class="px-4 py-3 font-medium">{{ $e->livre->titre }}</td>
                @if ($afficherAdherent)
                    <td class="px-4 py-3"><a href="{{ route('adherents.show', $e->adherent) }}" class="hover:underline">{{ $e->adherent->nomComplet() }}</a></td>
                @endif
                <td class="px-4 py-3">{{ $e->date_emprunt->format('d/m/Y') }}</td>
                <td class="px-4 py-3">{{ $e->date_retour_prevue->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    @if ($e->estEnRetard())
                        <span class="rounded-full bg-red-600 px-2 py-0.5 text-xs font-semibold text-white">En retard · {{ $e->joursDeRetard() }} j</span>
                    @elseif ($e->estEnCours())
                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">En cours</span>
                    @else
                        <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-800">Rendu le {{ $e->date_retour_effective->format('d/m/Y') }}</span>
                    @endif
                </td>
                @if ($afficherAdherent)
                    <td class="px-4 py-3 text-right">
                        @if ($e->estEnCours())
                            <button
                                type="button"
                                data-return-dialog-open
                                data-return-url="{{ route('emprunts.retour', $e) }}"
                                data-book-title="{{ $e->livre->titre }}"
                                class="rounded bg-emerald-600 px-3 py-1 text-white hover:bg-emerald-700"
                            >Enregistrer le retour</button>
                        @endif
                    </td>
                @endif
            </tr>
        @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">Aucun emprunt.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<dialog
    data-return-dialog
    aria-labelledby="return-dialog-title"
    aria-describedby="return-dialog-description"
    class="m-auto w-[calc(100%-2rem)] max-w-md rounded-xl p-0 shadow-xl backdrop:bg-slate-900/50"
>
    <div class="p-6">
        <h2 id="return-dialog-title" class="text-lg font-semibold text-slate-900">Confirmer le retour</h2>
        <p id="return-dialog-description" class="mt-2 text-sm text-slate-600">
            Voulez-vous enregistrer le retour du livre
            <span data-return-book-title class="font-semibold text-slate-800"></span> ?
        </p>
        <form method="POST" action="#" data-return-form class="mt-6 flex justify-end gap-3">
            @csrf
            <button
                type="button"
                data-return-dialog-close
                class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >Annuler</button>
            <button
                type="submit"
                class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
            >Confirmer le retour</button>
        </form>
    </div>
</dialog>
