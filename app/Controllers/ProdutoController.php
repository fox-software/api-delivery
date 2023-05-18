<?php

namespace App\Controllers;

use Exception;
use App\Libraries\AwsS3;

use App\Models\CategoriaModel;
use App\Models\ProdutoModel;

class ProdutoController extends BaseController
{
    protected $produtoModel;
    protected $categoriaModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        $filtros = $this->request->getVar();

        $data = [
            "page" => "produtos",
            "page_title" => "Produtos",
            "produtos" => $this->produtoModel->getAll($filtros),
            "categorias" => $this->categoriaModel->getAll(["status" => ATIVO]),
            "filtros" => $filtros
        ];

        return view('page/' . $data["page"], $data);
    }

    public function cadastrar()
    {
        $dados = $this->request->getVar();

        $validate = $this->validate([
            'foto' => [
                'uploaded[foto]',
                'mime_in[foto,image/jpg,image/jpeg,image/png]',
                'max_size[foto,1024]',
            ]
        ]);

        $img = $this->request->getFile('foto');

        if (isset($img) && $img->isValid()) {
            if (!$validate) {
                toast(TOAST_ERROR, "Falha", 'Tipo de arquivo não permitido!');
                return redirect()->to("admin/produtos");
            } else {
                $s3 = new AwsS3();
                $dados["foto"] = $s3->store($_FILES['foto']);
            }
        }

        try {
            $this->produtoModel->insert($dados);

            toast(TOAST_SUCCESS, "Sucesso", "Produto salvo com sucesso!");

            return redirect()->to("admin/produtos");
        } catch (Exception $e) {
            toast(TOAST_SUCCESS, "Erro", $e->getMessage());
            return redirect()->to("admin/produtos");
        }
    }

    public function status(int $id)
    {
        $dados = $this->request->getVar();

        $produto = $this->produtoModel->find($id);
        $novo_status = $produto["status"] == ATIVO ? INATIVO : ATIVO;

        try {
            $this->produtoModel->update($produto["id"], ["status" => $novo_status]);
            toast(TOAST_SUCCESS, "Sucesso", "Produto salvo com sucesso!");
        } catch (Exception $e) {
            toast(TOAST_SUCCESS, "Falha", $e->getMessage());
        }

        if (!empty($dados)) {
            return redirect()->to("admin/" . $dados["redirect"]);
        }

        return redirect()->to("admin/produtos");
    }

    public function editar(int $id)
    {
        $dados = $this->request->getVar();

        $validate = $this->validate([
            'foto' => [
                'uploaded[foto]',
                'mime_in[foto,image/jpg,image/jpeg,image/png]',
                'max_size[foto,1024]',
            ]
        ]);

        $img = $this->request->getFile('foto');

        if (isset($img) && $img->isValid()) {
            if (!$validate) {
                toast(TOAST_ERROR, "Falha", 'Tipo de arquivo não permitido!');
                return redirect()->to("admin/produtos");
            } else {
                $s3 = new AwsS3();
                $dados["foto"] = $s3->store($_FILES['foto']);
            }
        }

        $produto = $this->produtoModel->find($id);

        try {
            $this->produtoModel->update($produto["id"], $dados);
            toast(TOAST_SUCCESS, "Sucesso", "Produto salvo com sucesso!");
            return redirect()->to("admin/produtos");
        } catch (Exception $e) {
            toast(TOAST_ERROR, "Falha", $e->getMessage());
            return redirect()->to("admin/produtos");
        }
    }
}
