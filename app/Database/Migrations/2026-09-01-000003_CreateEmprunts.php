<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMembers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'     => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'members_id' => ['type' => 'INTEGER', 'unsigned' => true],
            'livres_id' => ['type' => 'INTEGER', 'unsigned' => true ],
            'date_emprunt' => ['type' => 'DATE', 'null' => true, 'default' => 'CURRENT_DATE'],
            'date_retour' => ['type' => 'DATE', 'null' => true, 'default' => null],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('members_id', 'members', 'members.id', '', '');
        $this->forge->addForeignKey('livres_id', 'livres', 'livres.id', '', '');
        $this->forge->createTable('emprunts');
    }

    public function down()
    {
        $this->forge->dropTable('emprunts');
    }
}
