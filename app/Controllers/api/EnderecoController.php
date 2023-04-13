<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

use App\Models\EnderecoModel;
use App\Models\UsuarioModel;

class EnderecoController extends ResourceController
{
  use ResponseTrait;

  protected $enderecoModel;
  protected $usuarioModel;

  public function __construct()
  {
    $this->enderecoModel = new EnderecoModel();
    $this->usuarioModel = new UsuarioModel();
  }

  public function index()
  {
    $usuario_id = $this->usuarioModel->getAuthenticatedUser();

    $data = $this->enderecoModel->getByUserId($usuario_id);

    return $this->respond($data);
  }

  public function cadastrar()
  {
    $data = $this->request->getVar();

    $usuario_id = $this->usuarioModel->getAuthenticatedUser();
    
    $resultado = $this->enderecoModel->cadastrar($usuario_id, $data);

    return $this->respond($resultado);
  }

  public function editar()
  {
    $data = $this->request->getVar();

    $usuario_id = $this->usuarioModel->getAuthenticatedUser();

    $resultado = $this->enderecoModel->editar($usuario_id, $data);

    return $this->respond($resultado);
  }
}
