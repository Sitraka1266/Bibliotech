<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpruntRequest;
use App\Models\Adherent;
use App\Models\Emprunt;
use App\Models\Livre;
use DomainException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmpruntController extends Controller
{
    public const MAX_EMPRUNTS = 1;

    public const DUREE_DEFAUT = 14;

    /** F7 : emprunts en cours avec mise en évidence des retards */
    public function index(Request $request)
    {
        $tous = $request->boolean('tous');

        /** @var Collection<int, Emprunt> $emprunts */
        $emprunts = Emprunt::query()
            ->with(['livre', 'adherent'])
            ->when(! $tous, fn ($q) => $q->enCours())
            ->orderByRaw('date_retour_effective IS NOT NULL')
            ->orderBy('date_retour_prevue')
            ->get();

        return view('emprunts.index', compact('emprunts', 'tous'));
    }

    public function create(Request $request)
    {
        $livreId = filter_var($request->old('livre_id'), FILTER_VALIDATE_INT);
        $adherentId = filter_var($request->old('adherent_id'), FILTER_VALIDATE_INT);

        return view('emprunts.create', [
            'livreSelectionne' => is_int($livreId) && $livreId > 0 ? Livre::find($livreId) : null,
            'adherentSelectionne' => is_int($adherentId) && $adherentId > 0 ? Adherent::find($adherentId) : null,
            'dureeDefaut' => self::DUREE_DEFAUT,
        ]);
    }

    public function rechercherLivres(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ]);
        $terme = trim($validated['q']);

        $livres = Livre::query()
            ->select(['id', 'titre', 'auteur', 'isbn', 'quantite_disponible'])
            ->where('quantite_disponible', '>', 0)
            ->where(function ($query) use ($terme) {
                $query->where('titre', 'like', "%{$terme}%")
                    ->orWhere('auteur', 'like', "%{$terme}%")
                    ->orWhere('isbn', 'like', "%{$terme}%");
            })
            ->orderBy('titre')
            ->limit(15)
            ->get()
            ->map(fn (Livre $livre): array => [
                'id' => $livre->id,
                'text' => "{$livre->titre} — {$livre->auteur} ({$livre->quantite_disponible} dispo.)",
            ]);

        return response()->json(['results' => $livres]);
    }

    public function rechercherAdherents(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ]);
        $terme = trim($validated['q']);

        $adherents = Adherent::query()
            ->select(['id', 'nom', 'prenom', 'email', 'telephone'])
            ->where(function ($query) use ($terme) {
                $query->where('nom', 'like', "%{$terme}%")
                    ->orWhere('prenom', 'like', "%{$terme}%")
                    ->orWhere('email', 'like', "%{$terme}%")
                    ->orWhere('telephone', 'like', "%{$terme}%");
            })
            ->orderBy('nom')
            ->orderBy('prenom')
            ->limit(15)
            ->get()
            ->map(fn (Adherent $adherent): array => [
                'id' => $adherent->id,
                'text' => $adherent->nomComplet().' — '.$adherent->email,
            ]);

        return response()->json(['results' => $adherents]);
    }

    /** F4 : règles de gestion 1, 2, 3 et 4 */
    public function store(EmpruntRequest $request)
    {
        $data = $request->validated();
        $duree = (int) ($data['duree'] ?? self::DUREE_DEFAUT);

        try {
            DB::transaction(function () use ($data, $duree) {
                $livre = Livre::lockForUpdate()->findOrFail($data['livre_id']);
                $adherent = Adherent::findOrFail($data['adherent_id']);

                if ($livre->quantite_disponible <= 0) {
                    throw new DomainException("« {$livre->titre} » n'est plus disponible.");
                }
                if ($adherent->emprunts()->enCours()->count() >= self::MAX_EMPRUNTS) {
                    throw new DomainException("{$adherent->nomComplet()} a déjà ".self::MAX_EMPRUNTS.' emprunts en cours.');
                }
                if ($adherent->aUnRetard()) {
                    throw new DomainException("{$adherent->nomComplet()} a un retard non rendu et ne peut pas emprunter.");
                }

                Emprunt::create([
                    'livre_id' => $livre->id,
                    'adherent_id' => $adherent->id,
                    'date_emprunt' => today(),
                    'date_retour_prevue' => today()->addDays($duree),
                ]);
                $livre->decrement('quantite_disponible');
            });
        } catch (DomainException $e) {
            return back()->withInput()->withErrors(['emprunt' => $e->getMessage()]);
        }

        return redirect()->route('emprunts.index')->with('success', 'Emprunt enregistré.');
    }

    /** F5 */
    public function retour(Emprunt $emprunt)
    {
        if (! $emprunt->estEnCours()) {
            return back()->withErrors(['emprunt' => 'Ce livre a déjà été rendu.']);
        }

        DB::transaction(function () use ($emprunt) {
            $emprunt->update(['date_retour_effective' => today()]);
            $emprunt->livre->increment('quantite_disponible');
        });

        return back()->with('success', 'Retour enregistré : le livre est de nouveau en stock.');
    }

    /** F12 : export CSV filtrable des emprunts */
    public function export(Request $request)
    {
        $filters = $request->validate([
            'statut' => ['sometimes', Rule::in(['en_cours', 'historique', 'tous'])],
            'periode' => ['nullable', 'integer', 'min:1', 'max:120'],
            'unite' => ['nullable', 'required_with:periode', Rule::in(['semaines', 'mois'])],
        ]);
        $statut = $filters['statut'] ?? 'en_cours';

        /** @var Collection<int, Emprunt> $emprunts */
        $emprunts = Emprunt::query()
            ->with(['livre', 'adherent'])
            ->when($statut === 'en_cours', fn ($query) => $query->enCours())
            ->when($statut === 'historique', fn ($query) => $query->whereNotNull('date_retour_effective'))
            ->when(isset($filters['periode']), function ($query) use ($filters) {
                $dateDebut = match ($filters['unite']) {
                    'semaines' => today()->subWeeks((int) $filters['periode']),
                    'mois' => today()->subMonths((int) $filters['periode']),
                };

                $query->whereDate('date_emprunt', '>=', $dateDebut->toDateString())
                    ->whereDate('date_emprunt', '<=', today()->toDateString());
            })
            ->orderBy('date_emprunt')
            ->orderBy('id')
            ->get();

        $nomFichier = 'emprunts_'.$statut;
        if (isset($filters['periode'])) {
            $nomFichier .= '_'.$filters['periode'].'_'.$filters['unite'];
        }

        return response()->streamDownload(function () use ($emprunts) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 pour Excel
            fputcsv($out, ['Livre', 'ISBN', 'Adhérent', 'Email', 'Date emprunt', 'Retour prévu', 'Date retour effectif', 'Statut', 'En retard', 'Jours de retard'], ';');
            foreach ($emprunts as $e) {
                fputcsv($out, [
                    $e->livre->titre,
                    $e->livre->isbn,
                    $e->adherent->nomComplet(),
                    $e->adherent->email,
                    Carbon::parse($e->date_emprunt)->format('d/m/Y'),
                    Carbon::parse($e->date_retour_prevue)->format('d/m/Y'),
                    $e->date_retour_effective ? Carbon::parse($e->date_retour_effective)->format('d/m/Y') : '',
                    $e->estEnCours() ? 'En cours' : 'Historique',
                    $e->estEnRetard() ? 'Oui' : 'Non',
                    $e->joursDeRetard(),
                ], ';');
            }
            fclose($out);
        }, $nomFichier.'_'.today()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
