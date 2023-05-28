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

  public function checkoutCreditCard($usuario_id, $dados)
  {
    $usuario = $this->usuarioModel->getUserCompleteById($usuario_id);
    $sistema = $this->sistemaModel->getById(get_sistema_api());

    // CRIAR USUÁRIO NO PAGARME
    $responseUsuarioPagarme = $this->pagarme->criarUsuarioPagarme($usuario);

    if (empty($responseUsuarioPagarme))
      throw new Exception("Não foi possível criar um cliente no gateway");

    // CRIAR CARTÃO NO PAGARME
    $responseCartaoPagarme = $this->pagarme->criarCartaoUsuarioPagarme($responseUsuarioPagarme["data"]["customer"]->id, $dados->cartao);

    if (!$responseCartaoPagarme["success"])
      throw new Exception($responseCartaoPagarme["message"]);

    // DADOS PAGAMENTO
    $responseDadosPagamento = $this->pagarme->dadosPagamentoCartaoCredito($usuario["id"], $usuario, $sistema, $dados);

    // CRIAR PAGAMENTO NO PAGARME
    $responsePagamento = $this->pagarme->criarPagamentoCartaoCredito($responseDadosPagamento);

    return $responsePagamento;
  }
}
