<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table = 'members';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nom', 'email', 'password'];

    protected $validationRules = [
        'nom'      => 'required|max_length[100]',
        'email'    => 'required|valid_email|is_unique[members.email,id,{id}]',
        'password' => 'required|min_length[6]',
    ];

    protected $validationMessages = [
        'nom'      => ['required' => 'Le nom est obligatoire.'],
        'email'    => [
            'required'    => "L'email est obligatoire.",
            'valid_email' => "L'email n'est pas valide.",
            'is_unique'   => "Cet email est déjà utilisé.",
        ],
        'password' => [
            'required' => "Le mot de passe est obligatoire.",
            'min_length' => "Le mot de passe doit faire au moins 6 caractères.",
        ]
    ];
}
