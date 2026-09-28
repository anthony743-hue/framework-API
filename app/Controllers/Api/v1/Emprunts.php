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

        $erreur = $this->verifierRelations($data['members_id'] ?? null, $data['livres_id'] ?? null);
        if ($erreur !== null) {
            return $erreur;
        }

        if ($this->model->save($data) === false) {
            return $this->failValidationErrors($this->model->errors(), 400);
        }

        $data['id'] = $this->model->getInsertID();
        return $this->respondCreated(["messages" => $data]);
    }

    // PUT 
    public function update($id = null)
    {
        $emprunt = $this->model->find($id);
        if (!$emprunt) {
            return $this->failNotFound("Emprunt introuvable");
        }

        $data = $this->request->getJSON(true);

        if ($this->request->getMethod() === 'put') {
            $data = array_merge($emprunt, $data);
        }

        

        if ($this->model->update($id, $data) === false) {
            return $this->failValidationErrors($this->model->errors(), 400);
        }
        $emprunt = $this->model->find($id);
        return $this->respond(["messages" => $emprunt], 200);
    }

    // GET /api/members/{id}/emprunts
    public function empruntsDuMembre($id = null, $emprunt_id = null)
    {
        $membre = model(MemberModel::class)->find($id);
        if (!$membre) {
            return $this->failNotFound("Membre introuvable");
        }

        $emprunts = $this->model->findByMember((int) $id);

        if ($emprunt_id !== null) {
            $emprunts = array_values(array_filter(
                $emprunts,
                static fn($e) => (int) $e['id'] === (int) $emprunt_id
            ));

            if (empty($emprunts)) {
                return $this->failNotFound("Emprunt introuvable pour ce membre");
            }

            return $this->respond([
                "membre"  => $membre,
                "donnees" => $emprunts[0],
            ]);
        }

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

    // ✅ Objectif 4 : créer un emprunt pour 1 utilisateur pour 1 livre
    public function createPourMembre($id = null, $livre_id = null)
    {
        $erreur = $this->verifierRelations($id, $livre_id);
        if ($erreur === false) {
            return $this->fail("");
        }

        $data =  $this->model
            ->where('livres_id', $livre_id)
            ->where('date_retour', null)
            ->sort('data_emprunt')
            ->first();

        $data = [
            'members_id'   => (int) $id,
            'livres_id'    => (int) $livre_id,
            'date_emprunt' => date('Y-m-d'),
        ];
        if ($this->request->getMethod() === 'post') {
            $data['date_retour']  = null;
        }

        if ($this->model->save($data) === false) {
            return $this->failValidationErrors($this->model->errors(), 400);
        }

        $data['id'] = $this->model->getInsertID();
        return $this->respondCreated(["messages" => $data]);
    }

    // Vérifie que le membre + le livre existent, et que le livre est dispo
    private function verifierRelations($members_id, $livres_id)
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

        if ($dejaEmprunte) {
            return $this->fail("Ce livre est déjà emprunté et non rendu", 409);
        }

        return false;
    }
}
