<?= $this->extend('layout/template') ?>

<?= $this->section('template') ?>
<section class="content">
  <div class="container-fluid">

    <div class="card">
      <div class="card-header">
        <form action="<?= base_url("admin/usuarios") ?>" method="get" class="form form-inline">
          <div class="d-flex justify-content-between w-100">
            <div class="d-flex" style="gap: 5px;">

              <select class="custom-select" id="status" name="status">
                <option value="">Selecione um status</option>
                <option value="A" <?= isset($filtros["status"]) && $filtros["status"] == "A" ? "selected" : "" ?>>Ativo</option>
                <option value="I" <?= isset($filtros["status"]) && $filtros["status"] == "I" ? "selected" : "" ?>>Inativo</option>
              </select>

              <input type="search" name="search" placeholder="Pesquisar" class="form-control" value="<?= isset($filtros["search"]) ? $filtros["search"] : "" ?>">
              <button type="submit" class="btn btn-dark">
                Filtrar
              </button>
            </div>
            <!-- <button type="button" class="btn btn-dark" title="Adicionar" data-toggle="modal" data-target="#add-categoria">
              <i class="fa fa-plus" aria-hidden="true"></i>
            </button> -->
          </div>
        </form>
      </div>
      <div class="card-body">
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>#</th>
              <th>Nome</th>
              <th>E-mail</th>
              <th>CPF</th>
              <th>Telefone</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($usuarios as $item) : ?>
              <tr>
                <td><?= $item["id"] ?></td>
                <td><?= $item["nome"] . " " . $item["sobrenome"] ?></td>
                <td><?= $item["email"] ?></td>
                <td><?= format_cpf_cnpj($item["cpf"]) ?></td>
                <td><?= format_phone($item["telefone"]) ?></td>
                <td>
                  <a href="<?= base_url("admin/usuarios/" . $item["id"] . "/status") ?>" class="btn btn-<?= $item["status"] == "A" ? "success" : "danger" ?>">
                    <i class="fa fa-<?= $item["status"] == "A" ? "check-circle" : "times-circle" ?>"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>


<?= $this->endSection() ?>