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
                                        <ul class="lista-feriados" id="lista-feriados" style="margin-left: 10px;"></ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!--Formulario Feriado-->
                        <form id="form-create-holiday">
                            <div class="container-upload">
                                <label class="label-feriado">Adicione nome e data para adicionar um Feriado</label>
                                <div class="item-horario item-ponto-facultativo">
                                    <div class="item-upload">
                                        <span class="error-mensagem"></span>
                                        <span>Nome do Feriado</span>
                                        <input class="texto-input" name="adicionar-nome-feriado" type="text" required>
                                        <span>Data do Feridado</span>
                                        <input type="date" class="horario-input" id="adicionar-dataferiado"
                                            name="data-feriado" required>
                                    </div>
                                </div>
                                <div class="items-botoes botoes-feriado">
                                    <button class="btn-calendario btn-feriado" type="submit">Adicionar Feriado</button>
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
                                <span>Configurações de Ponto Facultativo:</span>
                            </div>
                        </div>
                        <form action="./settings/registrar_ponto_facultativo.php" method="post"
                            enctype="multipart/form-data">
                            <div class="container-upload">
                                <label class="label-feriado">Informações do Ponto Facultativo</label>
                                <div class="item-horario item-ponto-facultativo">
                                    <div class="item-upload">
                                        <span>Data do Ponto Facultativo</span>
                                        <input type="date" class="horario-input" id="data-feriado" name="data-feriado"
                                            required>
                                    </div>
                                    <div class="item-upload">
                                        <span>Selecione o Feriado Relacionado</span>
                                        <select class="dropdown-feriado" name="nome-feriado" id="nome-feriado">
                                            <option hidden selected>Selecione</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="container-input">
                                    <div class="item-horario item-ponto-facultativo">
                                        <span>Selecione o tipo de funcionario</span>
                                        <select class="dropdown-feriado" name="selecao-tipo-funcionario[0]"
                                            id="selecao-tipo-funcionario" required>
                                            <option value="" disabled hidden selected>Selecione</option>
                                            <option value="Servidor Público">Servidor Publico</option>
                                            <option value="Estagiario">Estágiario</option>
                                            <option value="Ambos">Ambos</option>
                                        </select>
                                    </div>
                                    <span>Selecione o periodo da compensação</span>
                                    <div class="item-horario item-ponto-facultativo">
                                        <div class="item-upload">
                                            <span>Data de Inicio</span>
                                            <input type="date" class="horario-input data-periodo" id="horairo-inicio"
                                                name="inicio-ponto-facultativo[0]" required>
                                        </div>
                                        <div class="item-upload">
                                            <span>Data de Fim</span>
                                            <input type="date" class="horario-input data-periodo" id="horairo-fim"
                                                name="fim-ponto-facultativo[0]" required>
                                        </div>
                                    </div>
                                    <div class="container-servidor-publico">
                                        <span>Servidor Público</span>
                                        <div class="item-horario item-ponto-facultativo">
                                            <div class="row-servidor">
                                                <div class="items-horarios input-feriado">
                                                    <span>Entrada</span>
                                                    <input class="horario-input" maxlength="5" type="time"
                                                        id="servidor-publico-entrada"
                                                        name="servidor-publico-entrada[0]">
                                                </div>
                                                <div class="items-horarios input-feriado">
                                                    <span>Intervalo inicio</span>
                                                    <input class="horario-input" maxlength="5" type="time"
                                                        id="servidor-publico-saida-almoco"
                                                        name="servidor-publico-saida-almoco[0]">
                                                </div>
                                            </div>
                                            <div class="row-servidor">
                                                <div class="items-horarios input-feriado">
                                                    <span>Intervalo volta</span>
                                                    <input class="horario-input" maxlength="5" type="time"
                                                        id="servidor-publico-volta-almoco"
                                                        name="servidor-publico-volta-almoco[0]">
                                                </div>
                                                <div class="items-horarios input-feriado">
                                                    <span>Saida</span>
                                                    <input class="horario-input" maxlength="5" type="time"
                                                        id="servidor-publico-saida" name="servidor-publico-saida[0]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-estagiario">
                                        <span>Estágiario - Manhã</span>
                                        <div class="item-horario item-ponto-facultativo">
                                            <div class="items-horarios um-input input-feriado">
                                                <span>Entrada</span>
                                                <input class="horario-input" maxlength="5" type="time"
                                                    id="estagiario-manha-entrada" name="estagiario-manha-entrada[0]">
                                            </div>
                                            <div class="items-horarios um-input input-feriado">
                                                <span>Saida</span>
                                                <input class="horario-input" maxlength="5" type="time"
                                                    id="estagiario-manha-saida" name="estagiario-manha-saida[0]">
                                            </div>
                                        </div>
                                        <span>Estágiario - Tarde</span>
                                        <div class="item-horario item-ponto-facultativo">
                                            <div class="items-horarios um-input input-feriado">
                                                <span>Entrada</span>
                                                <input class="horario-input" maxlength="5" type="time"
                                                    id="estagiario-tarde-entrada" name="estagiario-tarde-entrada[0]">
                                            </div>
                                            <div class="items-horarios um-input input-feriado">
                                                <span>Saida</span>
                                                <input class="horario-input " maxlength="5" type="time"
                                                    id="estagiario-tarde-saida" name="estagiario-tarde-saida[0]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="items-botoes botoes-feriado">
                                    <button class="btn-calendario btn-feriado adicionar-div" type="button"><i
                                            class="fa-solid fa-plus"></i></button>
                                    <button class="btn-calendario btn-feriado" type="submit">Enviar</button>
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