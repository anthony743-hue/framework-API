<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmpruntSeeder extends Seeder
{
    public function run()
    {
        // s

        // Emprunts
        // La colonne date_emprunt est maintenant DATETIME : on ajoute l'heure.
        $this->db->table('emprunts')->insertBatch([
            ['id' => 1, 'members_id' => 21, 'livres_id' => 2, 'date_emprunt' => '2026-09-28 09:15:00', 'date_retour' => null],
            ['id' => 2, 'members_id' => 21, 'livres_id' => 4, 'date_emprunt' => '2026-09-01 14:30:00', 'date_retour' => '2026-09-15'],
            ['id' => 3, 'members_id' => 22, 'livres_id' => 6, 'date_emprunt' => '2026-09-20 18:45:00', 'date_retour' => null],
            ['id' => 4, 'members_id' => 23, 'livres_id' => 3, 'date_emprunt' => '2026-08-10 11:00:00', 'date_retour' => '2026-08-25'],
        ]);
    }
}