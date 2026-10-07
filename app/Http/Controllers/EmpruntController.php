<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpruntRequest;
use App\Models\Adherent;
use App\Models\Emprunt;
use App\Models\Livre;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpruntController extends Controller
{
    public const MAX_EMPRUNTS = 3;
    public const DUREE_DEFAUT = 14;

    /** F7 : emprunts en cours avec mise en évidence des retards */
    public function index(Request $request)
    {
        $tous = $request->boolean('tous');

        $emprunts = Emprunt::query()
            ->with(['livre', 'adherent'])
            ->when(! $tous, fn ($q) => $q->enCours())
            ->orderByRaw('date_retour_effective IS NOT NULL')
            ->orderBy('date_retour_prevue')
            ->get();

        return view('emprunts.index', compact('emprunts', 'tous'));
    }

    public function create()
    {
        return view('emprunts.create', [
            'livres' => Livre::where('quantite_disponible', '>', 0)->orderBy('titre')->get(),
            'adherents' => Adherent::orderBy('nom')->orderBy('prenom')->get(),
            'dureeDefaut' => self::DUREE_DEFAUT,
        ]);
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

    /** F12 : export CSV des emprunts en cours */
    public function export()
    {
        $emprunts = Emprunt::enCours()->with(['livre', 'adherent'])->orderBy('date_retour_prevue')->get();

        return response()->streamDownload(function () use ($emprunts) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 pour Excel
            fputcsv($out, ['Livre', 'ISBN', 'Adhérent', 'Email', 'Date emprunt', 'Retour prévu', 'En retard', 'Jours de retard'], ';');
            foreach ($emprunts as $e) {
                fputcsv($out, [
                    $e->livre->titre,
                    $e->livre->isbn,
                    $e->adherent->nomComplet(),
                    $e->adherent->email,
                    $e->date_emprunt->format('d/m/Y'),
                    $e->date_retour_prevue->format('d/m/Y'),
                    $e->estEnRetard() ? 'Oui' : 'Non',
                    $e->joursDeRetard(),
                ], ';');
            }
            fclose($out);
        }, 'emprunts_en_cours_'.today()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
