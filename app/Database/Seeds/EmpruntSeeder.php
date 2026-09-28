<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmpruntSeeder extends Seeder
{
    public function run()
    {
        // Membres
        $this->db->table('members')->insertBatch([
            ['id' => 21, 'nom' => 'Alice Dupont',  'email' => 'alice@example.com', 'password' => 'secret123'],
            ['id' => 22, 'nom' => 'Bob Martin',    'email' => 'bob@example.com',   'password' => 'secret123'],
            ['id' => 23, 'nom' => 'Carol Bernard', 'email' => 'carol@example.com', 'password' => 'secret123'],
        ]);

        // Emprunts
        $this->db->table('emprunts')->insertBatch([
            ['id' => 1, 'members_id' => 21, 'livres_id' => 12, 'date_emprunt' => '2026-09-28', 'date_retour' => null],
            ['id' => 2, 'members_id' => 21, 'livres_id' => 14, 'date_emprunt' => '2026-09-01', 'date_retour' => '2026-09-15'],
            ['id' => 3, 'members_id' => 22, 'livres_id' => 16, 'date_emprunt' => '2026-09-20', 'date_retour' => null],
            ['id' => 4, 'members_id' => 23, 'livres_id' => 13, 'date_emprunt' => '2026-08-10', 'date_retour' => '2026-08-25'],
        ]);
    }
}