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

    <!-- Estrutura de Criação de usuario -->

    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome welcome-white">
                        <h1 class="title-container">Cadastro de novo Úsuario</h1>
                    </div>
                </div>
        </section>
        <section class="form-section">
            <div class="section-container">
                <div class="form">
                    <div class="">
                        <form id="form-create-user">
                            <div class="container-formulario">
                                <div class="container-item">
                                    <div class="item-formulario">
                                        <label >Nome Completo:</label>
                                        <input type="text" class="input-login" name="nome" id="nome_completo" required>
                                    </div>
                                    <div class="item-formulario">
                                        <label >Nome de acesso:</label>
                                        <input type="text" class="input-login" name="username" id="username" required>
                                    </div>
                                    <div class="row-formulario">
                                        <div class="item-formulario">
                                            <label >Email:</label>
                                            <input type="email" class="input-login" name="email" id="email" required>
                                        </div>

                                        <div class="item-formulario">
                                            <label >Setor:</label>
                                            <select class="input-login" name="setor" id="setor" required>
                                                <option value="" hidden>Selecione um setor</option>
                                                <option value="CAC">CAC</option>
                                                <option value="COAFI">COAFI</option>
                                                <option value="COBI">COBI</option>
                                                <option value="CODIN">CODIN</option>
                                                <option value="COESE">COESE</option>
                                                <option value="COESP">COESP</option>
                                                <option value="COEST">COEST</option>
                                                <option value="COICID">COICID</option>
                                                <option value="COINF">COINF</option>
                                                <option value="COPESE">COPESE</option>
                                                <option value="COPES">COPES</option>
                                                <option value="COREF">COREF</option>
                                                <option value="CPL">CPL</option>
                                                <option value="DIGER">DIGER</option>
                                                <option value="DIPEQ">DIPEQ</option>
                                                <option value="DIREST">DIREST</option>
                                                <option value="DISTAT">DISTAT</option>
                                                <option value="LIMITES">LIMITES</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row-formulario">
                                        <div class="item-formulario">
                                            <label >CPF:</label>
                                            <input type="text" class="input-login" name="cpf" id="cpf"
                                                placeholder="000.000.000-00" maxlength="14" required>
                                        </div>
                                        <div class="item-formulario">
                                            <label >Cargo:</label>
                                            <select class="input-login" name="cargo" id="cargo" required>
                                                <option value="" hidden>Selecione um cargo</option>
                                                <option value=1>Servidor Público</option>
                                                <option value=2>PPE - Primeiro Emprego</option>
                                                <option value=3>Estágiario-Manhã</option>
                                                <option value=4>Estágiario-Tarde</option>
                                                <option value=5>Coordenador</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row-formulario">
                                        <div class="item-formulario">
                                            <label >Senha:</label>
                                            <input type="password" class="input-login" name="senha" id="senha" required>
                                        </div>
                                        <div class="item-formulario">
                                            <label >Confirmar Senha:</label>
                                            <input type="password" class="input-login" name="senha-confirmar"
                                                id="senha-confirmar" required>
                                        </div>
                                    </div>
                                    <div class="row-formulario">
                                        <label >Úsuario tem permissões de Administrador?</label>
                                        <input type="checkbox" name="permissao" id="checkbox">
                                    </div>
                                    <div class="item-formulario"><input type="submit" value="Criar usuario" class="btn-login">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>
    </div>
    <script type="module" src=<?= SCRIPT_URL . "/page/createUser.js" ?>></script>

</body>

</html>