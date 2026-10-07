<?php

namespace Database\Seeders;

use App\Models\Livre;
use Illuminate\Database\Seeder;

class LivreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $livres = [
            ['Dadabe', 'Michèle Rakotoson', '9782865370764', 'Roman', 1984, 2],
            ['Le bain des reliques', 'Michèle Rakotoson', '9782865372188', 'Roman', 1988, 2],
            ['Elle, au printemps', 'Michèle Rakotoson', '9782907888646', 'Roman', 1996, 2],
            ['Lalana', 'Michèle Rakotoson', '9782876787834', 'Roman', 2002, 2],
            ['Juillet au pays', 'Michèle Rakotoson', '9782914659888', 'Roman', 2007, 2],
            ['Tovonay, l’enfant du Sud', 'Michèle Rakotoson', '9782842801595', 'Roman', 2010, 2],
            ['Presque-songes', 'Jean-Joseph Rabearivelo', '9782842801199', 'Poésie', 2006, 2],
            ['Poèmes', 'Jean-Joseph Rabearivelo', '9782218027734', 'Poésie', 1960, 2],
            ['L’Interférence', 'Jean-Joseph Rabearivelo', '9782218017193', 'Roman', 1987, 2],
            ['Traduit de la nuit', 'Jean-Joseph Rabearivelo', '9782729104665', 'Poésie', 1990, 2],
            ['Rêves sous le linceul', 'Jean-Luc Raharimanana', '9782753800083', 'Roman', 2004, 2],
            ['L’arbre anthropophage', 'Jean-Luc Raharimanana', '9782070789498', 'Récit', 2004, 2],
            ['Za', 'Jean-Luc Raharimanana', '9782848761053', 'Roman', 2008, 2],
            ['Madagascar, 1947', 'Jean-Luc Raharimanana', '9782911412493', 'Roman', 2007, 2],
            ['Les cauchemars du gecko', 'Jean-Luc Raharimanana', '9782911412790', 'Roman', 2011, 2],
            ['Revenir', 'Jean-Luc Raharimanana', '9782743643379', 'Récit', 2018, 2],
            ['Au-delà des rizières', 'Naivo', '9782842801991', 'Roman historique', 2012, 2],
            ['Pirogue sur le vide', 'David Jaomanoro', '9782752601797', 'Nouvelles', 2006, 2],
            ['Le mangeur de cactus', 'David Jaomanoro', '9782336301877', 'Récit', 2013, 2],
            ['Le cinquième sceau', 'Charlotte-Arrisoa Rafenomanjato', '9782738419965', 'Roman', 1993, 2],
        ];

        foreach ($livres as [$titre, $auteur, $isbn, $categorie, $annee, $quantite]) {
            Livre::firstOrCreate(
                ['isbn' => $isbn],
                [
                    'titre' => $titre,
                    'auteur' => $auteur,
                    'categorie' => $categorie,
                    'annee' => $annee,
                    'quantite_totale' => $quantite,
                    'quantite_disponible' => $quantite,
                ],
            );
        }
    }
}