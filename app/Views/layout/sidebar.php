<?= $this->section('sidebar') ?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url("/") ?>" class="brand-link">
    <img src="<?= base_url("assets/img/AdminLTELogo.png") ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Adega Online</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
      <div class="image">
        <img src="<?= session()->get("sistema")["foto"] ?>" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= session()->get("sistema")["nome_fantasia"] ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
          <a href="<?= base_url("admin/dashboard") ?>" class="nav-link <?= $page_title === "dashboard" ? "active" : "" ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url("admin/painel") ?>" class="nav-link <?= $page_title === "painel" ? "active" : "" ?>">
            <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
            <i class="nav-icon fa fa-th-large" aria-hidden="true"></i>

            <p>Painel</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url("admin/categorias") ?>" class="nav-link <?= $page_title === "categorias" ? "active" : "" ?>">
            <i class="nav-icon fa fa-tags"></i>
            <p>Categorias</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url("admin/produtos") ?>" class="nav-link <?= $page_title === "produtos" ? "active" : "" ?>">
            <i class="nav-icon fa fa-pizza-slice"></i>
            <p>Produtos</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url("admin/usuarios") ?>" class="nav-link <?= $page_title === "usuarios" ? "active" : "" ?>">
            <i class="nav-icon fa fa-users"></i>
            <p>Clientes</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="../widgets.html" class="nav-link">
            <i class="nav-icon fa fa-scroll"></i>
            <p>Pedidos</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url("admin/forma_de_pagamentos") ?>" class="nav-link <?= $page_title === "forma_de_pagamentos" ? "active" : "" ?>">
            <i class="nav-icon fas fa-credit-card"></i>
            <p>Formas de Pagamentos</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url("admin/configuracao") ?>" class="nav-link <?= $page_title === "configurações" ? "active" : "" ?>">
            <i class="nav-icon fa fa-cog"></i>
            <p>Configurações</p>
          </a>
        </li>


        <li class="nav-item">
          <a href="<?= base_url("logout") ?>" class="nav-link bg-light">
            <i class="nav-icon fas fa-sign-out-alt color-custom-primary"></i>
            <p class="color-custom-primary fw-bold">Sair</p>
          </a>
        </li>



        <!-- NAV COM OPÇÕES -->

        <!-- <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Menu
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../../index.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Dashboard v1</p>
              </a>
            </li>
          </ul>
        </li> -->

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

<?= $this->endSection() ?>