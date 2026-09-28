<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMembers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'     => ['type' => 'INTEGER', 'unsigned' => true, 'auto_increment' => true],
            'nom'  => ['type' => 'VARCHAR', 'constraint' => 200],
            'email' => ['type' => 'VARCHAR', 'constraint' => 120],
            'password'  => ['type' => 'VARCHAR', 'constraint' => 300],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('members');
    }

    public function down()
    {
        $this->forge->dropTable('members');
    }
}
