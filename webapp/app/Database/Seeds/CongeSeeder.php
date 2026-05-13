<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CongeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'employe_id' => 3,
                'type_conge_id' => 1,
                'date_debut' => '2026-06-01',
                'date_fin' => '2026-06-05',
                'nb_jours' => 5,
                'motif' => 'Vacances',
                'statut' => 'approuve',
                'commentaire_rh' => 'Validé',
                'created_at' => date('Y-m-d H:i:s'),
                'traite_par' => 2
            ],
            [
                'employe_id' => 3,
                'type_conge_id' => 2,
                'date_debut' => '2026-07-10',
                'date_fin' => '2026-07-12',
                'nb_jours' => 3,
                'motif' => 'Grippe',
                'statut' => 'en_attente',
                'commentaire_rh' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'traite_par' => null
            ],
        ];

        $this->db->table('conges')->insertBatch($data);
    }
}