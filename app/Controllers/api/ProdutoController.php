<?php

namespace App\Controllers\Api;

use App\Models\ProdutoModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ProdutoController extends ResourceController
{
    use ResponseTrait;

    protected $produtoModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
    }

    public function index()
    {
        $data = $this->produtoModel
            ->select("produtos.*")
            ->join("categorias", "categorias.id = produtos.categoria_id")
            ->where("categorias.sistema_id", get_sistema())
            ->where("produtos.status", "A")
            ->findAll();

        return $this->respond($data);
    }
}
