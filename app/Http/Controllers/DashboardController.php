<?php

namespace App\Http\Controllers;

use App\Models\Adherent;
use App\Models\Emprunt;
use App\Models\Livre;

class DashboardController extends Controller
{
    /** F8 */
    public function __invoke()
    {
        return view('dashboard', [
            'nbLivres' => Livre::count(),
            'nbAdherents' => Adherent::count(),
            'nbEnCours' => Emprunt::enCours()->count(),
            'nbRetards' => Emprunt::enRetard()->count(),
            'retards' => Emprunt::enRetard()->with(['livre', 'adherent'])->orderBy('date_retour_prevue')->limit(5)->get(),
        ]);
    }
}
