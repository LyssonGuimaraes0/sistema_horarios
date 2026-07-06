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

    <!-- Estrutura da Home -->

    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome welcome-primary">
                        <h1 class="title-container">Gerenciar Feriados</h1>
                    </div>
                </div>
                <div class="container-dropdown">
                    <div class="row-dropdown">
                        <span>Selecione a tarefa:</span>
                        <select class="dropdown" style="width:215px;">
                            <option value="" select hidden>Selecione</option>
                            <option value=1>Gerenciar Feriado</option>
                            <option value=2>Gerenciar Ponto Facultativo</option>
                        </select>
                    </div>
                </div>

                <!--Container de Feriados-->
                <div class="container-feriado">
                    <div id="container-feriado">
                        <div class="calendario-header">

                            <div class="calendario-titulo">
                                <span>Adicionar Feriado:</span>
                            </div>
                        </div>
                        <!--Accordion Feriados-->
                        <div class="container-upload">
                            <div class="accordion">
                                <div class="accordion-item">
                                    <div class="accordion-header">Verificar Feriados do Ano</div>
                                    <div class="accordion-content">
                                        <ul class="lista-feriados" id="lista-feriados">
                                            <template id="item-feriado">
                                                <li>
                                                    <div class="item-feriado">
                                                        <span></span>
                                                        <i class="fa-solid fa-x"></i>
                                                    </div>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!--Formulario Feriado-->
                        <form id="form-create-holiday">
                            <div class="container-upload" style="width: 600px;">
                                <label class="label-feriado">Adicione nome e data para adicionar um Feriado</label>
                                <div class="item-horario item-ponto-facultativo">
                                    <div class="item-upload">
                                        <span class="error-mensagem"></span>
                                        <span>Nome do Feriado</span>
                                        <input class="texto-input" name="name" type="text" required>
                                        <span>Data do Feridado</span>
                                        <input type="date" class="horario-input" id="adicionar-dataferiado" name="date"
                                            required>
                                    </div>
                                    <div class="items-botoes botoes-feriado">
                                        <button class="btn-calendario btn-feriado" type="submit">Cadastrar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="container-feriado">
                    <!--Container de Ponto Facultativo-->
                    <div id="container-ponto-facultativo">
                        <div class="calendario-header">
                            <div class="calendario-titulo">
                                <span>Gerenciar Ponto Facultativo:</span>
                            </div>
                        </div>
                        <form id="form-create-ponto-facultativo">
                            <div class="container-upload">
                                <div class="container-input">
                                    <div class="item-horario item-ponto-facultativo">
                                        <div class="item-upload">
                                            <span>Data do Ponto Facultativo</span>
                                            <input type="date" class="horario-input" name="date" required>
                                        </div>
                                    </div>
                                    <br>
                                    <span>Selecione o periodo da compensação</span>
                                    <div class="item-horario item-ponto-facultativo" id="container-date">
                                        <div class="item-upload">
                                            <span>Data de Inicio</span>
                                            <input type="date" class="horario-input data-periodo" name="dataStart"
                                                required>
                                        </div>
                                        <div class="item-upload">
                                            <span>Data de Fim</span>
                                            <input type="date" class="horario-input data-periodo" name="dataEnd"
                                                required>
                                        </div>
                                    </div>
                                    <span>Selecione o horario de compensação</span>
                                    <br>
                                    <small>Caso não precise adicionar horario, deixe os campos vazios</small>
                                    <div class="container-servidores">
                                        <div class="container-holiday container-servidor-publico"
                                            data-tipo="Servidor Publico">
                                            <span>Servidor Público</span>
                                            <div class="item-horario item-ponto-facultativo">
                                                <div class="row-servidor">
                                                    <div class="items-horarios input-feriado">
                                                        <div class="items-horarios um-input input-feriado">
                                                            <span>Entrada</span>
                                                            <input class="horario-input" maxlength="5" type="time"
                                                                name="entrada">
                                                        </div>
                                                    </div>
                                                    <div class="items-horarios input-feriado">
                                                        <div class="items-horarios um-input input-feriado">
                                                            <span>Intervalo inicio</span>
                                                            <input class="horario_input" maxlength="5" type="time"
                                                                name="saida-almoco">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row-servidor">
                                                    <div class="items-horarios input-feriado">
                                                        <div class="items-horarios um-input input-feriado">
                                                            <span>Intervalo volta</span>
                                                            <input class="horario-input" maxlength="5" type="time"
                                                                name="volta_almoco">
                                                        </div>
                                                    </div>
                                                    <div class="items-horarios input-feriado">
                                                        <div class="items-horarios um-input input-feriado">
                                                            <span>Saida</span>
                                                            <input class="horario-input" maxlength="5" type="time"
                                                                name="saida">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="container-holiday container-estagiario" data-tipo="Estagiario">
                                            <span>Estágiario - Manhã</span>
                                            <div class="item-horario item-ponto-facultativo ">
                                                <div class="items-horarios um-input input-feriado">
                                                    <span>Entrada</span>
                                                    <input class="horario-input" maxlength="5" type="time"
                                                        name="manha_entrada">
                                                </div>
                                                <div class="items-horarios um-input input-feriado">
                                                    <span>Saida</span>
                                                    <input class="horario-input" maxlength="5" type="time"
                                                        name="manha_saida">
                                                </div>
                                            </div>
                                            <span>Estágiario - Tarde</span>
                                            <div class="item-horario item-ponto-facultativo">
                                                <div class="items-horarios um-input input-feriado">
                                                    <span>Entrada</span>
                                                    <input class="horario-input" maxlength="5" type="time"
                                                        name="tarde_entrada">
                                                </div>
                                                <div class="items-horarios um-input input-feriado">
                                                    <span>Saida</span>
                                                    <input class="horario-input " maxlength="5" type="time"
                                                        name="tarde_saida">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="items-botoes botoes-feriado">
                                        <button class="btn-calendario btn-feriado" type="submit">Cadastrar</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script type="module" src=<?= SCRIPT_URL . "/page/holiday.js" ?>></script>

</body>

</html>