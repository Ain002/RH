<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => 'Informatique',
                'description' => 'Développement et maintenance'
            ],
            [
                'nom' => 'RH',
                'description' => 'Gestion du personnel'
            ],
            [
                'nom' => 'Comptabilite',
                'description' => 'Gestion financière'
            ],
        ];

        $this->db->table('departements')->insertBatch($data);
    }
}