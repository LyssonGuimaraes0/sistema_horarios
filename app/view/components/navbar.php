<!--Chama Helper Permission--->

<?php

use App\helpers\PermissionHelper;

?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header" id="sidebar-toggle">
        <i class="fa-solid fa-bars logo-icon"></i>
        <span class="logo-text">Sistema</span>
    </div>

    <ul class="sidebar-menu">
        <li class="nav-link">
            <a href=<?= BASE_URL  . "/user/dashboard"?>>
                <i class="fa-solid fa-table-columns"></i>
                <span class="menu-text">HomePage</span>
            </a>
        </li>

            <li class="nav-link">
                <a href=<?= BASE_URL  . "/user/attendance"?>>
                    <i class="fa-solid fa-clock card-icon"></i>
                    <span class="menu-text">Registrar Ponto</span>
                </a>
            </li>
        <!--------------------------------->


<!--             <li class="nav-link">
                <a href= /* BASE_URL  . "/coordinator/attendance-validations" */>
                    <i class="fa-solid fa-address-book"></i>
                    <span class="menu-text">Gerenciar Frequencias</span>
                </a>
            </li> -->

        <!--------------------------------->

        <!--Verifica se os usuarios é Coordenadores ou Administrador para Buscar Usuario-->

        <?php if (PermissionHelper::isAdmin($user)): ?>
            <li class="nav-link">
                <a href=<?= BASE_URL  . "/user/management"?>>
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span class="menu-text">Gerenciar Usuarios</span>
                </a>
            </li>
        <?php endif; ?>

        <!--------------------------------->

        <!--Verifica se os usuarios é Administrador para Funções de Gerenciar Usuarios e dados-->

        <?php if (PermissionHelper::isAdmin($user)): ?>

            <li class="nav-link">
                <a href=<?= BASE_URL  . "/admin/attendance/holiday"?>>
                    <i class="fa-regular fa-calendar-check"></i>
                    <span class="menu-text">Gerenciar Feriado</span>
                </a>
            </li>

            <li class="nav-link">
                <a href=<?= BASE_URL  . "/user/create"?>>
                    <i class="fa-solid fa-user-plus"></i>
                    <span class="menu-text">Novo Usuario</span>
                </a>
            </li>

        <?php endif; ?>

        <!--------------------------------->

        <li class="nav-link">
            <a id="btn-logout">
                <i class="fa-solid fa-house-chimney-window"></i>
                <span class="menu-text">Sair</span>
            </a>
        </li>
    </ul>
</aside>

<script type="module" src= <?= SCRIPT_URL . "/utils/logout.js" ?>></script>