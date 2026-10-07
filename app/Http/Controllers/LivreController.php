<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivreRequest;
use App\Models\Livre;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    /** F1 + F6 (recherche, filtres) + F10 (pagination) */
    public function index(Request $request)
    {
        $livres = Livre::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $s = trim($request->q);
                $query->where(fn ($w) => $w->where('titre', 'like', "%{$s}%")
                    ->orWhere('auteur', 'like', "%{$s}%"));
            })
            ->when($request->filled('categorie'), fn ($query) => $query->where('categorie', $request->categorie))
            ->when($request->boolean('disponibles'), fn ($query) => $query->where('quantite_disponible', '>', 0))
            ->orderBy('titre')
            ->paginate(8)
            ->withQueryString();

        $categories = Livre::query()->distinct()->orderBy('categorie')->pluck('categorie');

        return view('livres.index', compact('livres', 'categories'));
    }

    public function create()
    {
        return view('livres.create', ['livre' => new Livre()]);
    }

    public function store(LivreRequest $request)
    {
        $data = $request->validated();
        $data['quantite_disponible'] = $data['quantite_totale'];
        Livre::create($data);

        return redirect()->route('livres.index')->with('success', 'Livre ajouté avec succès.');
    }

    public function edit(Livre $livre)
    {
        return view('livres.edit', compact('livre'));
    }

    public function update(LivreRequest $request, Livre $livre)
    {
        $data = $request->validated();
        // quantite_disponible = total - exemplaires actuellement empruntés (règles 2 et 6)
        $data['quantite_disponible'] = $data['quantite_totale'] - $livre->nbEmpruntsEnCours();
        $livre->update($data);

        return redirect()->route('livres.index')->with('success', 'Livre modifié avec succès.');
    }

    public function destroy(Livre $livre)
    {
        // Règle 5
        if ($livre->emprunts()->enCours()->exists()) {
            return back()->withErrors(['suppression' => "Suppression impossible : « {$livre->titre} » a des emprunts en cours."]);
        }
        $livre->delete();

        return redirect()->route('livres.index')->with('success', 'Livre supprimé.');
    }
}
