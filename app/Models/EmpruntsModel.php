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
    public function findByMemberOrByEmprunt($members_id,$emprunt_id=null)
    {
        return $this->where('members_id', $members_id)
                    ->orWhere('emprunt_id', $emprunt_id)
                    ->findAll();
    }

    public function justifyEmpruntExistence($members_id, $livres_id)
    {
        if ($members_id == null || $livres_id == null) {
            return false;
        }

        $membre = model(MemberModel::class)->find($members_id);
        if (!$membre) {
            return false;
        }

        $livre = model(LivreModel::class)->find($livres_id);
        if (!$livre) {
            return false;
        }

        $dejaEmprunte = $this->model
            ->where('livres_id', $livres_id)
            ->where('date_retour', null)
            ->sort('data_emprunt')
            ->first();

        return $dejaEmprunte === null;
    }
}