<?php

namespace Database\Seeders;

use App\Models\Adherent;
use Illuminate\Database\Seeder;

class AdherentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adherents = [
            ['Rakoto', 'Andry', 'andry.rakoto@example.com', '0341100001', 3],
            ['Rasoa', 'Hanta', 'hanta.rasoa@example.com', '0341100002', 3],
            ['Randria', 'Mamy', 'mamy.randria@example.com', '0341100003', 3],
            ['Rabe', 'Lala', 'lala.rabe@example.com', null, 3],
            ['Razafy', 'Tojo', 'tojo.razafy@example.com', '0341100005', 3],
            ['Rakotoarisoa', 'Fara', 'fara.rakotoarisoa@example.com', '0341100006', 2],
            ['Andriamihaja', 'Tiana', 'tiana.andriamihaja@example.com', '0341100007', 2],
            ['Ravelomanana', 'Miora', 'miora.ravelomanana@example.com', '0341100008', 2],
            ['Rasolofo', 'Hery', 'hery.rasolofo@example.com', '0341100009', 2],
            ['Andrianina', 'Soa', 'soa.andrianina@example.com', '0341100010', 2],
            ['Ranaivo', 'Lova', 'lova.ranaivo@example.com', '0341100011', 2],
            ['Razafindrakoto', 'Fanja', 'fanja.razafindrakoto@example.com', '0341100012', 2],
        ];

        foreach ($adherents as [$nom, $prenom, $email, $telephone, $moisInscrit]) {
            Adherent::firstOrCreate(
                ['email' => $email],
                [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'telephone' => $telephone,
                    'date_inscription' => today()->subMonths($moisInscrit),
                ],
            );
        }
    }
}
