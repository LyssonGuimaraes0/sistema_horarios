<!DOCTYPE html>
<html lang="pt-BR">
<!--Chama Helper Permission--->

<?php use App\helpers\PermissionHelper; ?>

<?php include_once COMPONENTS_PATH . "/head.php"; ?>

<body>

    <!--NavBar-->
    <?php include_once COMPONENTS_PATH . "/navbar.php"; ?>

    <!--Modal-->
    <?php include_once COMPONENTS_PATH . "/modal.php"; ?>

    <!-- Estrutura da Página de Gerenciamento de usuario -->

    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome welcome-white">
                        <h1 class="title-container">Gerenciar Usuarios</h1>
                    </div>
                </div>
                <div class="container-home container-down">
                    <div class="container-dropdown">
                        <div class="row-dropdown">
                            <span>Selecione um setor:</span>
                            <select class="dropdown" name="setor" id="dropdown-setor">
                                <option value="" selected hidden>Selecione Um setor</option>
                            </select>
                        </div>
                    </div>

                    <!--                     <div class="container-dropdown" id="container-pessoas">
                        <div class="row-dropdown">
                            <span>Selecione uma pessoa:</span>
                            <select class="dropdown" name="pessoa-seletor" id="dropdown-pessoas"></select>
                            <option value="" selected hidden>Selecione o usuario</option>
                            <button class="btn-formulario btn-registrar" id="btn-search-user">Buscar</button>
                        </div>
                    </div> -->
                </div>

                <!-- Container de Selecionar Usuario -->

                <div class="container-home" id="container-pessoas">
                    <div class="container-calendario calendario-container">
                        <div class="loading-overlay hidden"></div>

                        <table class="tabela-usuarios">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Cargo</th>
                                    <th>Status</th>
                                    <th>ID</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody class="list-user-calendario">
                                <template id="card-manager-user">
                                    <tr class="card-item">
                                        <td>
                                            <div class="user-cell">
                                                <div class="avatar"></div>
                                                <div>
                                                    <div class="user-name"><span></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td id="user-cargo"><span></span></td>
                                        <td>Ativo</td>
                                        <td id="user-id"><span></span></td>
                                        <td>
                                            <i class="fa-solid fa-pen-to-square botao-calendario"></i>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Container de dados do usuario -->

                <!-- <div class="container-home" id="container-user-dados">
                    <div class="container-calendario calendario-container">
                        <div class="loading-overlay hidden"></div>
                        <div class="calendario-header">
                            <div class="calendario-titulo">
                                <span></span>
                            </div>
                            <div class="calendario-header-btns">
                                <button class="btn-formulario-header" id="btn-anexar-frequencia">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <p>Editar Usuario</p>
                                </button>
                                <button class="btn-formulario-header" id="btn-gerar-frequencia">
                                    <i class="fa-solid fa-file-alt"></i>
                                    <p>Anexar Atestado</p>
                                </button>
                            </div>
                        </div>
                        <div class="calendario-body">
                            <span>Dados do Perfil:</span>
                            <table>
                                <thead>
                                    <th>Email:</th>
                                    <th>CPF</th>
                                    <th>Cargo</th>
                                    <th>Permissões</th>
                                    <th>Nome de acesso</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-item='email'></td>
                                        <td data-item='cpf'></td>
                                        <td data-item='cargo'></td>
                                        <td data-item='permissoes'></td>
                                        <td data-item='username'></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> -->


            </div>
        </section>
    </div>

    <script type="module" src=<?= SCRIPT_URL . "/page/manageUser.js" ?>></script>

</body>

</html>