<?php

namespace Database\Seeders;

use App\Models\Adherent;
use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Compte bibliothécaire (F9)
        User::firstOrCreate(
            ['email' => 'bibliothecaire@bibliotech.test'],
            ['name' => 'Bibliothécaire', 'password' => 'password']
        );

        // Idempotent : ne reseme pas si les données existent déjà
        if (Livre::count() > 0) {
            return;
        }

        $livres = [
            ['Le Petit Prince', 'Antoine de Saint-Exupéry', '9782070612758', 'Roman', 1943, 3],
            ['Les Misérables', 'Victor Hugo', '9782253096344', 'Roman', 1862, 2],
            ['L\'Étranger', 'Albert Camus', '9782070360024', 'Roman', 1942, 2],
            ['Clean Code', 'Robert C. Martin', '9780132350884', 'Informatique', 2008, 2],
            ['Introduction à l\'algorithmique', 'Thomas H. Cormen', '9782100545261', 'Informatique', 2010, 1],
            ['Sapiens', 'Yuval Noah Harari', '9782226257017', 'Histoire', 2011, 3],
            ['Une brève histoire du temps', 'Stephen Hawking', '9782081397835', 'Sciences', 1988, 2],
            ['Rovako', 'Jean-Joseph Rabearivelo', '9782914095013', 'Poésie', 1934, 1],
            ['Astérix le Gaulois', 'René Goscinny', '9782012101333', 'Bande dessinée', 1961, 4],
            ['Germinal', 'Émile Zola', '9782253004226', 'Roman', 1885, 2],
        ];
        foreach ($livres as [$titre, $auteur, $isbn, $categorie, $annee, $qte]) {
            Livre::create([
                'titre' => $titre, 'auteur' => $auteur, 'isbn' => $isbn,
                'categorie' => $categorie, 'annee' => $annee,
                'quantite_totale' => $qte, 'quantite_disponible' => $qte,
            ]);
        }

        $adherents = [
            ['Rakoto', 'Andry', 'andry.rakoto@example.com', '0341100001'],
            ['Rasoa', 'Hanta', 'hanta.rasoa@example.com', '0341100002'],
            ['Randria', 'Mamy', 'mamy.randria@example.com', '0341100003'],
            ['Rabe', 'Lala', 'lala.rabe@example.com', null],
            ['Razafy', 'Tojo', 'tojo.razafy@example.com', '0341100005'],
        ];
        foreach ($adherents as [$nom, $prenom, $email, $tel]) {
            Adherent::create([
                'nom' => $nom, 'prenom' => $prenom, 'email' => $email,
                'telephone' => $tel, 'date_inscription' => today()->subMonths(3),
            ]);
        }

        $emprunter = function (int $livreId, int $adherentId, int $joursDepuis, int $duree = 14, ?int $rendu = null) {
            $emprunt = Emprunt::create([
                'livre_id' => $livreId,
                'adherent_id' => $adherentId,
                'date_emprunt' => today()->subDays($joursDepuis),
                'date_retour_prevue' => today()->subDays($joursDepuis)->addDays($duree),
                'date_retour_effective' => $rendu !== null ? today()->subDays($rendu) : null,
            ]);
            if ($rendu === null) {
                Livre::whereKey($livreId)->decrement('quantite_disponible');
            }

            return $emprunt;
        };

        // 1 emprunt EN RETARD (Andry : emprunté il y a 20 jours, retour prévu il y a 6 jours)
        $emprunter(5, 1, 20);
        // 2 emprunts en cours dans les délais
        $emprunter(1, 2, 3);
        $emprunter(4, 3, 1);
        // 1 emprunt déjà rendu (pour l'historique, F11)
        $emprunter(6, 2, 30, 14, 20);
    }
}
