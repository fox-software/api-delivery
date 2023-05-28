<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

use App\Libraries\Pagarme;

use App\Models\SistemaModel;
use App\Models\UsuarioModel;

use Exception;

class PagamentoController extends ResourceController
{
  use ResponseTrait;

  protected $usuarioModel;
  protected $sistemaModel;
  protected $pagarme;

  public function __construct()
  {
    $this->usuarioModel = new UsuarioModel();
    $this->sistemaModel = new SistemaModel();
    $this->pagarme = new Pagarme();
  }

  public function cartaoCredito($usuario_id, $dados)
  {
    $usuario = $this->usuarioModel->getUserCompleteById($usuario_id);
    $sistema = $this->sistemaModel->getById(get_sistema_api());

    // DADOS PAGAMENTO
    $responseDadosPagamento = $this->pagarme->dadosPagamentoCartaoCredito($usuario, $sistema, $dados);

    // CRIAR PAGAMENTO NO PAGARME
    $responsePagamento = $this->pagarme->criarPagamentoCartaoCredito($responseDadosPagamento);

    if (!$responsePagamento["success"])
      throw new Exception("Falha ao criar pedido");

    return $responsePagamento;
  }
}
