<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
  protected $table = 'pedidos';
  protected $primaryKey = 'id';
  protected $allowedFields = ['usuario_id', 'sistema_id', 'cartao_id', 'endereco_id', 'total', 'frete', 'forma_pagamento_id', 'status', 'codigo', 'troco', 'comprovante', 'observacao'];
  protected $validationRules = [];

  public function getAll($filtros = [])
  {
    $resultado = $this
      ->select("pedidos.*, enderecos.endereco, CONCAT(usuarios.nome, ' ', usuarios.sobrenome) AS usuario_nome, forma_pagamentos.descricao AS forma_pagamento")
      ->join('usuarios', 'usuarios.id = pedidos.usuario_id')
      ->join('forma_pagamentos', 'forma_pagamentos.id = pedidos.forma_pagamento_id')
      ->join('enderecos', 'enderecos.id = pedidos.endereco_id', 'LEFT')
      ->where("sistema_id", session()->get("sistema")["id"])
      ->orderBy('updated_at', 'DESC');

    if (!empty($filtros["status"])) {
      $resultado->where("pedidos.status", $filtros["status"]);
    }

    if (!empty($filtros["search"])) {
      $resultado->like(["usuarios.nome" => $filtros["search"]]);
      $resultado->orLike(["usuarios.sobrenome" => $filtros["search"]]);
    }
    

    return $resultado->findAll();
  }

  public function getAllForChart($ano = 2023)
  {
    $resultado = $this->select("COUNT(pedidos.id) AS total, EXTRACT(MONTH FROM created_at) mes, EXTRACT(YEAR FROM created_at) ano")
      ->where("pedidos.sistema_id", session()->get("sistema")["id"])
      ->where("status", RECEBIDO)
      ->where('YEAR(created_at)', $ano)
      ->groupBy("EXTRACT(MONTH FROM created_at), EXTRACT(YEAR FROM created_at)")
      ->orderBy("ano", "DESC")
      ->orderBy("mes", "ASC")
      ->findAll();

    $meses = ["0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"];

    for ($i = 0; $i < count($resultado); $i++) {
      for ($y = 0; $y < count($meses); $y++) {
        if ($y + 1 == $resultado[$i]["mes"]) {
          $meses[$y] = $resultado[$i]["total"];
        }
      }
    }

    return $meses;
  }

  public function getTotal($ano)
  {
    $resultado = $this->select("SUM(pedidos.total) AS total")
      ->where("status", RECEBIDO)
      ->where("pedidos.sistema_id", session()->get("sistema")["id"])
      ->where('YEAR(created_at)', $ano)
      ->first();

    return empty($resultado["total"]) ? format_money(0) : format_money($resultado["total"]);
  }

  public function getPorcentagem($ano)
  {
    $resultado = $this->select("COUNT(pedidos.id) AS total, EXTRACT(MONTH FROM created_at) mes, EXTRACT(YEAR FROM created_at) ano")
      ->where("status", RECEBIDO)
      ->where("pedidos.sistema_id", session()->get("sistema")["id"])
      ->where('YEAR(created_at)', $ano)
      ->groupBy("EXTRACT(MONTH FROM created_at), EXTRACT(YEAR FROM created_at)")
      ->orderBy("ano", "DESC")
      ->orderBy("mes", "DESC")
      ->limit(2)
      ->findAll();

    $base_porcentagem = 0;
    $porcentagem_atual = 0;
    if (count($resultado) >= 2) {
      for ($i = 0; $i < count($resultado); $i++) {
        $base_porcentagem = $resultado[1]["total"] * 100 / 100;
        $porcentagem_atual = $resultado[0]["total"] * 100 / $base_porcentagem;
      }
    }

    return empty($porcentagem_atual) ? 0 : format_money($porcentagem_atual, false);
  }

  public function cadastrar($usuario_id, $data)
  {
    $this->db->transBegin();

    $pedidoProdutoModel = new PedidoProdutoModel();

    $data->forma_pagamento_id = (int) $data->forma_pagamento->id;
    $data->usuario_id = (int) $usuario_id;
    $data->cartao_id = (int) $data->cartao->id;
    $data->endereco_id = (int) $data->endereco->id;
    $data->sistema_id = (int) get_sistema_api();

    $data->codigo = rand(1, 100); //Código será gerado pelo pagarme

    $this->save($data);

    $pedido_id = $this->getInsertID();

    $pedidoProdutoModel->cadastrar($pedido_id, $data->carrinho);

    if ($this->db->transStatus() === FALSE) {
      $this->db->transRollback();
      return [
        "status" => false,
        "message" => "Ocorreu uma falha ao salvar!"
      ];
    } else {
      $this->db->transCommit();
      return [
        "status" => true,
        "message" => "Pedido salvo com sucesso!"
      ];
    }
  }

  public function kanban($tipo)
  {
    date_default_timezone_set('America/Sao_Paulo');

    $pedidoProdutoModel = new PedidoProdutoModel();

    $pedidos = $this->select("pedidos.*,
    CONCAT(usuarios.nome, ' ', usuarios.sobrenome) AS usuario_nome, enderecos.endereco, enderecos.cep, enderecos.numero, enderecos.cep, enderecos.complemento,DATE_FORMAT(pedidos.created_at, '%d/%m/%Y %T') AS data")
      ->join("usuarios", "pedidos.usuario_id = usuarios.id")
      ->join("enderecos", "pedidos.endereco_id = enderecos.id")
      ->where([
        "DATE_FORMAT(pedidos.created_at, '%Y-%m-%d')" => date("Y-m-d"),
        "sistema_id" => session()->get("sistema")["id"],
        "pedidos.status" => $tipo,
        "enderecos.status" => ATIVO,
      ])
      ->orderBy("pedidos.created_at", "DESC")
      ->findAll();

    for ($y = 0; $y < count($pedidos); $y++) {
      $pedidos[$y]["produtos"] = $pedidoProdutoModel
        ->select("produtos.*")
        ->join("produtos", "produtos.id = pedidos_produtos.produto_id")
        ->where("pedido_id", $pedidos[$y]["id"])
        ->findAll();
    }

    return $pedidos;
  }

  public function setStatus($pedidoId, $status)
  {
    $resultado = $this->update($pedidoId, ["status" => $status]);

    return $resultado;
  }
}
