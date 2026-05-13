<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmployeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => 'Rakoto',
                'prenom' => 'Jean',
                'email' => 'admin@test.com',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'role' => 'admin',
                'departement_id' => 1,
                'date_embauche' => '2024-01-10',
                'actif' => 1
            ],
            [
                'nom' => 'Rabe',
                'prenom' => 'Sarah',
                'email' => 'rh@test.com',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'role' => 'rh',
                'departement_id' => 2,
                'date_embauche' => '2024-02-15',
                'actif' => 1
            ],
            [
                'nom' => 'Andry',
                'prenom' => 'Lucas',
                'email' => 'user@test.com',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'role' => 'employe',
                'departement_id' => 1,
                'date_embauche' => '2025-01-01',
                'actif' => 1
            ],
        ];

        $this->db->table('employes')->insertBatch($data);
    }
}