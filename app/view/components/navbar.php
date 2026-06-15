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
        <!--Verifica se os usuarios são Coordenadores ou PPE Para Registrar Horarios-->

        <?php if (
            !PermissionHelper::isPPE($user)
            &&
            !PermissionHelper::isCoordenador($user)
        ): ?>

            <li class="nav-link">
                <a href=<?= BASE_URL  . "/user/attendance"?>>
                    <i class="fa-solid fa-clock card-icon"></i>
                    <span class="menu-text">Registrar Ponto</span>
                </a>
            </li>

            <li class="nav-link">
                <a href="./anexar_frequencia.php">
                    <i class="fa-solid fa-print card-icon"></i>
                    <span class="menu-text">Anexar Frequência</span>
                </a>
            </li>
        <?php endif; ?>

        <!--------------------------------->

        <!--Verifica se os usuarios é Coordenador para Gerenciar Frequencias-->

        <?php if (PermissionHelper::isCoordenador($user)): ?>
            <li class="nav-link">
                <a href="./buscar_usuario.php">
                    <i class="fa-solid fa-address-book"></i>
                    <span class="menu-text">Gerenciar Frequencias</span>
                </a>
            </li>
        <?php endif; ?>

        <!--------------------------------->

        <!--Verifica se os usuarios é Coordenadores ou Administrador para Buscar Usuario-->

        <?php if (PermissionHelper::isAdmin($user) || PermissionHelper::isCoordenador($user)): ?>
            <li class="nav-link">
                <a href=<?= BASE_URL  . "/user/management"?>>
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span class="menu-text">Buscar usuario</span>
                </a>
            </li>
        <?php endif; ?>

        <!--------------------------------->

        <!--Verifica se os usuarios é Administrador para Funções de Gerenciar Usuarios e dados-->

        <?php if (PermissionHelper::isAdmin($user)): ?>

            <li class="nav-link">
                <a href="./feriado.php">
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