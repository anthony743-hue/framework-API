<?php

namespace App\Controllers\Api\V1;

use App\Models\EmpruntsModel;
use App\Models\LivreModel;
use App\Models\MemberModel;
use CodeIgniter\RESTful\ResourceController;

class Emprunts extends ResourceController
{
    protected $modelName = EmpruntsModel::class;
    protected $format    = 'json';

    // GET /api/emprunts/{id}
    public function show($id = null)
    {
        $emprunt = $this->model->find($id);
        $code    = $emprunt ? 200 : 404;
        return $this->respond(["donnees" => $emprunt], $code);
    }

    // POST /api/emprunts
    public function create()
    {
        $data = $this->request->getJSON(true);

        $erreur = $this->model->justifyEmpruntExistence($data['members_id'] ?? null, $data['livres_id'] ?? null);
        if ($erreur === false) {
            return $this->failNotFound("");
        }

        if ($this->model->save($data) === false) {
            return $this->failValidationErrors($this->model->errors(), 400);
        }

        $data['id'] = $this->model->getInsertID();
        return $this->respondCreated(["messages" => $data]);
    }

    // PATCH 
    public function update($emprunt_id = null)
    {
        $emprunt = $this->model->find($emprunt_id);
        if (!$emprunt) {
            return $this->failNotFound("Emprunt introuvable");
        }

        $data = array_merge($emprunt, $this->request->getJSON(true));
        if (empty($data['date_emprunt'])) {
            return $this->failValidationErrors("La date d'emprunt ne peut pas être vide.", 400);
        }

        if (isset($data['date_retour']) && !is_null($data['date_retour'])) {
            $dateEmprunt = new \DateTime($data['date_emprunt']);
            $dateRetour = new \DateTime($data['date_retour']);
            if ($dateEmprunt <= $dateRetour) {
                return $this->failValidationErrors("La date d'emprunt doit être postérieure à la date de retour.", 400);
            }
        }

        if ($this->model->update($emprunt_id, $data) === false) {
            return $this->failValidationErrors($this->model->errors(), 400);
        }
        $emprunt = $this->model->find($emprunt_id);
        return $this->respond(["messages" => $emprunt], 200);
    }

    // GET /api/members/{id}/emprunts
    public function empruntsDuMembre($id = null, $emprunt_id = null)
    {
        $membre = model(MemberModel::class)->find($id);
        if (!$membre) {
            return $this->failNotFound("Membre introuvable");
        }

        $emprunts = $this->model->findByMemberOrByEmprunt($id, $emprunt_id);

        return $this->respond([
            "membre"  => $membre,
            "total"   => count($emprunts),
            "donnees" => $emprunts,
        ]);
    }

    // GET /api/members/{id}/emprunts/{emprunt_id}
    public function empruntDuMembre($id = null, $emprunt_id = null)
    {
        return $this->empruntsDuMembre($id, $emprunt_id);
    }
}
