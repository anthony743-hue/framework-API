<?php

namespace App\Controllers\Api\V1;

use App\Models\MemberModel;
use CodeIgniter\RESTful\ResourceController;

class Members extends ResourceController
{
    protected $modelName = MemberModel::class;
    protected $format    = 'json';

    // GET /api/members
    public function index($page = 1, $par_page = 10)
    {
        $data = model(MemberModel::class)->findAll($par_page, $page);
        return $this->respond(["page" => $page, "par_page" => $par_page,
                            "total" => count($data),
                            "donnees" => $data]);
    }

    // GET /api/members/{id}
    public function show($id = null)
    {
        $member = $this->model->find($id);
        $code   = $member ? 200 : 404;
        return $this->respond(["donnees" => $member], $code);
    }

    // POST /api/members
    public function create()
    {
        $data = $this->request->getJSON(true);
        if ($this->model->save($data) === false) {
            return $this->failValidationErrors($this->model->errors(), 404);
        }
        $data['id'] = $this->model->getInsertID();
        return $this->respondCreated(["messages" => $data]);
    }

    // PUT et PATCH /api/members/{id} arrivent ici tous les deux.
    public function update($id = null)
    {
        $member = $this->model->find($id);
        $data = $this->request->getJSON(true);
        if ($this->request->getMethod() === 'put') {
            $data = array_merge($member, $data);
        }
        if ($this->model->update($id, $data) === false) {
            return $this->failValidationErrors($this->model->errors(), 404);
        }
        $member = $this->model->find($id);
        return $this->respond(["messages" => $member], 200);
    }

    // DELETE /api/members/{id}
    public function delete($id = null)
    {
        $member = $this->model->find($id);
        if (!$member) {
            return $this->failNotFound("Membre introuvable");
        }
        $this->model->delete($id);
        return $this->respondNoContent("Membre supprimé");
    }
}