<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpruntsModel extends Model
{
    protected $table         = 'emprunts';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['members_id', 'livres_id', 'date_emprunt', 'date_retour'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'members_id'   => 'required|integer|is_not_unique[members.id]',
        'livres_id'    => 'required|integer|is_not_unique[livres.id]',
        'date_emprunt' => 'permit_empty|valid_date[Y-m-d]',
        'date_retour'  => 'permit_empty|valid_date[Y-m-d]',
    ];

    protected $validationMessages = [
        'members_id' => [
            'required'      => "L'identifiant du membre est obligatoire.",
            'is_not_unique' => "Le membre référencé n'existe pas.",
        ],
        'livres_id' => [
            'required'      => "L'identifiant du livre est obligatoire.",
            'is_not_unique' => "Le livre référencé n'existe pas.",
        ],
    ];

    // Tous les emprunts d'un membre
    public function findByMember(int $members_id)
    {
        return $this->where('members_id', $members_id)->findAll();
    }
}