<!--Modal Default de avisos-->
<template id="modal-default">
    <div class="modal-background">
        <div class="section-container">
            <div class="modal-container">
                <div class="modal">
                    <div class="modal-cabecalho">
                        <!--Titulo-->
                    </div>
                    <div class="modal-descricao">
                        <!--Descrição-->
                    </div>
                    <div class="btn-modal">
                        <input type="button" id="botao-confirmar" value="Okay" class="btn-login">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!--Modal Anexo de Folha de ponto-->
<template id="modal-anexo">
    <div class="modal-background">
        <div class="section-container">
            <div class="modal-container">
                <div class="loading-overlay hidden"></div>
                <form>
                    <div class="container-home container-down">
                        <div class="container-anexar-frequencia">
                            <div class="calendario-header">
                                <div class="calendario-titulo">
                                    <span>Adicionar Anexo</span>
                                </div>
                            </div>
                            <div class="container-upload">
                                <span>Selecione o Anexo do Mês correspondente:</span>
                                <div class="item-upload">
                                    <smalL>Arquivo permitido: PDF</small>
                                    <input class='btn-upload' style='margin-bottom:10px;' type='file' name='anexo_mes'
                                        required>
                                    <input type='hidden' name='ano'>
                                    <input type='hidden' name='mes'>
                                    <div class="btn-modal ">
                                        <button type="button" class="btn-confirmar btn-padrao">Enviar</button>
                                        <button type="button" class="btn-padrao btn-cancelar">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<!--Modal de informações de usuarios-->

<template id="modal-info-user">
    <div class="modal-background">
        <div class="section-container">
            <div class="modal-container container-user-info">
                <div class="loading-overlay hidden"></div>
                <div class="modal">
                    <div class="modal-cabecalho modal-justifi">
                        <div class="container-welcome header-modal-user">
                            <h2 class="title-container">
                                Ficha do Usuario
                            </h2>
                            <button class="btn-formulario-header" id="btn-anexar-frequencia">
                                <i class="fa-solid fa-upload card-icon"></i>
                                <p>Anexar Atestado</p>
                            </button>
                        </div>
                    </div>
                    <div class="container-modal-user">
                        <div class="row-modal-user">
                            <div class="items-horarios item-modal-user">
                                <span>Nome completo</span>
                                <input class="horario-input input-modal-user" type="text" name="nome" readonly disabled>
                            </div>
                            <div class="items-horarios item-modal-user">
                                <span>Email</span>
                                <input class="horario-input input-modal-user" type="text" name="email" readonly
                                    disabled>
                            </div>
                        </div>
                        <div class="row-modal-user">
                            <div class="items-horarios item-modal-user">
                                <span>CPF</span>
                                <input class="horario-input input-modal-user" type="text" name="cpf" readonly disabled>
                            </div>
                            <div class="items-horarios item-modal-user">
                                <span>username</span>
                                <input class="horario-input input-modal-user" type="text" name="username" readonly
                                    disabled>
                            </div>
                        </div>
                        <div class="row-modal-user">
                            <div class="items-horarios item-modal-user">
                                <span>Permissões</span>
                                <input class="horario-input input-modal-user" type="text" name="permissoes" readonly
                                    disabled>
                            </div>
                            <div class="items-horarios item-modal-user">
                                <span>Cargo</span>
                                <input class="horario-input input-modal-user" type="text" name="cargo" readonly
                                    disabled>
                            </div>
                        </div>
                        <!-- Accordion -->
                        <div class="accordion">
                            <div class="accordion-item">
                                <button class="accordion-header">Folha de Ponto mensal</button>
                                <div class="accordion-content">
                                    <div class="row-dropdown">
                                        <span>Selecione o Ano:</span>
                                        <select class="dropdown" name="ano-seletor" id="select-folha-ponto">
                                        </select>
                                        <button class="btn-formulario btn-registrar"
                                            id="search-folha-mensal">Buscar</button>
                                    </div>
                                    <table id="tabela-folha-mensal">
                                        <thead>
                                            <th>Mes/Ano:</th>
                                            <th>Verificar Arquivo</th>
                                        </thead>
                                        <!-- Template td -->
                                        <template id="template-tr-folha-mensal">
                                            <tr>
                                                <td data-folha="month"></td>
                                                <td data-folha="file"></td>
                                            </tr>
                                        </template>
                                        <tbody id="container-td">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-modal ">
                        <button type="button" class="btn-editar btn-padrao">
                            <p>Editar</p>
                        </button>
                        <button type="button" class="btn-padrao btn-close">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>



<!-- Modal para anexar de justificativa -->
<template id="modalCertificate">
    <div class="modal-background">
        <div class="section-container">
            <div class="modal-container container-justificativa">
                <div class="modal">
                    <div class="modal-cabecalho modal-justifi">
                        <div class="container-welcome">
                            <h2 class="title-container">
                                Justifique sua Falta
                            </h2>
                        </div>
                    </div>
                    <div class="modal-descricao">

                        <div class="input-colunm grid-justificativa">
                            <div class="item-justificativa">
                                <span>Data:</span>
                                <input class='input-justificativa' id="data-origem" type="text" readonly>
                            </div>
                            <div class="item-justificativa">
                                <span>Dias Afastados</span>
                                <input class='input-justificativa' min="1" value="1" step="1" name="dias_atestado"
                                    id="input-dias-atestados" type="number">
                            </div>
                            <div>

                            </div>
                            <div class="item-justificativa">
                                <span>Inicio:</span>
                                <input class='input-justificativa' id="data-inicio" type="text" readonly>
                            </div>
                            <div class="item-justificativa">
                                <span>Fim</span>
                                <input class='input-justificativa' id="data-fim" type="text" readonly>
                            </div>
                            <div class="item-justificativa aviso-justificativa">
                                <span>retorno previsto</span>

                            </div>
                        </div>

                        <div class="item-justificativa">
                            <span>Tipo de justificativa</span>
                            <select name="descricao_motivo" class="dropdown dropdown-justificativa" required>
                                <option value="">Selecione o motivo da falta</option>
                                <!-- Saúde -->
                                <option value="consulta_medica">Consulta médica</option>
                                <option value="atestado_medico">Atestado médico por doença</option>
                                <option value="internacao_hospitalar">Internação hospitalar</option>
                                <option value="exames_medicos">Exames médicos</option>
                                <option value="cirurgia">Cirurgia</option>
                                <option value="atendimento_odontologico">Atendimento odontológico</option>
                                <option value="licenca_medica">Licença médica</option>
                                <option value="acompanhamento_dependente">Acompanhamento médico de dependente</option>
                                <option value="tratamento_continuo">Tratamento contínuo (fisioterapia, psicologia, etc.)
                                </option>
                                <!-- Obrigações legais -->
                                <option value="audiencia_judicial">Audiência judicial</option>
                                <option value="convocacao_jurado">Convocação como jurado</option>
                                <option value="convocacao_eleitoral">Convocação eleitoral</option>
                                <option value="comparecimento_delegacia">Comparecimento à delegacia</option>
                                <option value="convocacao_militar">Convocação militar</option>
                                <!-- Família -->
                                <option value="falecimento_familiar">Falecimento de familiar</option>
                                <option value="casamento">Casamento</option>
                                <option value="nascimento_filho">Nascimento de filho</option>
                                <option value="licenca_maternidade">Licença-maternidade</option>
                                <option value="acompanhamento_escolar">Acompanhamento escolar de filho</option>
                                <!-- Educação -->
                                <option value="prova_escolar">Prova escolar/universitária</option>
                                <option value="tcc_apresentacao">Apresentação de TCC</option>
                                <option value="concurso_vestibular">Concurso público / vestibular / ENEM</option>
                                <option value="treinamento_empresa">Treinamento obrigatório</option>
                                <!-- Emergências -->
                                <option value="acidente_transito">Acidente de trânsito</option>
                                <option value="boletim_ocorrencia">Boletim de ocorrência</option>
                                <option value="problema_transporte">Problema grave com transporte público</option>
                                <option value="desastre_natural">Desastre natural</option>
                                <!-- Documentação -->
                                <option value="emissao_documentos">Emissão/renovação de documentos</option>
                                <option value="comparecimento_inss">Comparecimento ao INSS</option>
                                <option value="pericia_medica">Perícia médica</option>
                                <!-- Outros -->
                                <option value="mudanca_residencial">Mudança residencial</option>
                                <option value="doacao_sangue">Doação de sangue</option>
                                <option value="doacao_medula">Doação de medula óssea</option>
                                <option value="greve_transporte">Greve de transporte</option>
                                <option value="doacao_medula">Outro tipo de Atestado</option>
                            </select>
                        </div>

                        <div class="item-justificativa item-upload">
                            <span>Adicione o Anexo correspondente:</span>
                            <smalL>Arquivo permitido: PDF</small>
                            <input class="btn-upload" type="file" name="justi_pdf" accept=".pdf" required>
                        </div>

                    </div>
                    <input type="hidden" name="data" id="data_justificativa">
                    <div class="btn-modal ">
                        <button type="button" class="btn-confirmar btn-padrao">Confirmar</button>
                        <button type="button" class="btn-padrao btn-cancelar">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!--Modal Toast-->
<template id="modal-toast">
    <div class="modal-toast">
        <div class="container-text">
            <p></p>
        </div>
    </div>
</template>