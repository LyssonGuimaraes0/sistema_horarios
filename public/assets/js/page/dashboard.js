import { request } from "../service/ajax.js";
import {
    showModalToast,
    apresentarModal,
    showModalCertificate
} from "../utils/modal.js";

import { verificarInputs } from "../utils/verifyInput.js";

import { gerarPeriodo } from "../utils/date.js";

import {
    alterButtonsCalendar,
    createButtonsCalendar,
    setReadonly,
    RemoveReadonly,
    buttonSubmitCalendar,
    certificateButtonCalendar
} from "../utils/card.js";

const formData = new FormData();

//formulario de input diario
const formRecordDashboard = document.querySelector('#form-record-dashboard');

//Verifica caso os inputs estão com valores

const inputs = [...formRecordDashboard.querySelectorAll('.horario-input')]

const todosPreenchidos = inputs.every(input => input.value !== "");

//Altera botões
if (todosPreenchidos) {
    const containerBtn = formRecordDashboard.querySelector('.items-botoes');
    createButtonsCalendar(containerBtn);

    inputs.forEach(input => {
        setReadonly(input);
    })
}


//Valida inputs de calendario
document.addEventListener('input', function (e) {
    //Retorna caso não seja inputs de horario
    if (!e.target.classList.contains('horario-input')) return;

    const input = e.target;
    // pega todos inputs da div correta
    const containerInputs = input.closest('.container-horarios');

    //Coleta inputs do container correto e converte para array
    const allinputs = Array.from(containerInputs.querySelectorAll('.horario-input'));

    const indexAllInputs = allinputs.indexOf(input);

    verificarInputs(input, indexAllInputs, allinputs)

});

//Objeto de estado de inputs
const state = {
    editando: false,
    dadosOriginais: {}
};

//Valida Cliques de botões gerados no formulario
formRecordDashboard.addEventListener('click', async (e) => {
    e.preventDefault()
    let resultado
    let response
    const btn = e.target.closest('.botao-calendario, .btn-calendario');
    if (!btn) return;

    const card = btn.closest('.container-horarios');
    const action = btn.dataset.action;

    //Coleta todos os inputs do card
    const inputs = card.querySelectorAll('.horario-input')

    let attendanceData = {}
    let inputsattendance = {};

    switch (action) {

        //===========Casos de Envio de formulario ==========  

        case 'submit':
            inputs.forEach(input => {
                //Armazena valores em objs
                inputsattendance[input.name] = input.value;
            });

            //Monta objeto de Horarios
            attendanceData = {
                date: card.dataset.date,
                status: "Completo",
                attendance: inputsattendance
            }

            //Enviar dados para o Backend
            try {
                response = await request(`${urlBase}/api/user/attendance/create`, {
                    method: "POST",
                    credentials: 'include',
                    body: { ...attendanceData }
                })

                if (!response?.success) {
                    throw new Error(
                        response.error ??
                        response.message ??
                        "Erro interno do servidor"
                    );
                }

                //Aplica estilos de cards com editados

                inputs.forEach(input => {
                    setReadonly(input)
                });

                //Altera botões do container btn
                createButtonsCalendar(card.querySelector('.items-botoes'));

                //Apresenta modal
                showModalToast(response.data);


            } catch (error) {
                showModalToast(error, "error");
            }

            break;

        //==========================================================================

        //================ Casos de edição de formulario já criado ==================  

        case 'edit':
            //Altera botões do calendario
            const newBtns = alterButtonsCalendar(card.querySelector('.items-botoes'));

            card.appendChild(newBtns)

            state.editando = true;

            //Remove bloqueio e armazena valores originais
            inputs.forEach(input => {
                state.dadosOriginais[input.name] = input.value;
                RemoveReadonly(input)
            });


            break;


        //Cancelar alteração de dados de formulario    
        case 'cancel':

            state.editando = false;

            //Remove bloqueio e armazena valores originais
            inputs.forEach(input => {
                input.value = state.dadosOriginais[input.name];
                setReadonly(input)
            });

            //Reabilita botão de edição
            const oldBtns = createButtonsCalendar(card.querySelector('.items-botoes'));

            card.appendChild(oldBtns)

            break;

        //Confirmar dados alterado de formulario    
        case 'confirm':

            //Garante que so seja enviado
            if (state.editando != true) return;

            inputs.forEach(input => {
                //Armazena valores em objs
                inputsattendance[input.name] = input.value;
            });

            console.log(inputsattendance);

            //Monta objeto de Horarios
            attendanceData = {
                date: card.dataset.date,
                attendance: inputsattendance
            }


            try {
                response = await request(`${urlBase}/api/user/attendance`, {
                    method: "PATCH",
                    credentials: 'include',
                    body: { ...attendanceData }
                })

                if (!response?.success) {
                    throw new Error(
                        response.error ??
                        response.message ??
                        "Erro interno do servidor"
                    );
                }

                //Aplica estilos de cards com editados

                inputs.forEach(input => {
                    setReadonly(input)
                });

                //Altera botões do container btn
                createButtonsCalendar(card.querySelector('.items-botoes'));

                //Apresenta modal
                showModalToast(response.data);


            } catch (error) {
                showModalToast(error, "error");
            }

            break;

        //==========================================================================



        //==================== Caso de adição de atestado ==========================  

        //Adicionar atestado por periodo
        case 'certificate':


            let resultadoCertificate = await showModalCertificate(card.dataset.date);

            //Chama rota de envio para atestado
            if (resultadoCertificate) {

                //Converte dados para form
                formData.append('dateStart', resultadoCertificate.dateStart);
                formData.append('dateEnd', resultadoCertificate.dateEnd);
                formData.append('descricao_motivo', resultadoCertificate.descricao_motivo);
                formData.append('file', resultadoCertificate.file);

                //Bloquear todos inputs dos dias selecionados
                const periodo = gerarPeriodo(
                    resultadoCertificate.dateStart
                    , resultadoCertificate.dateEnd
                );


                try {
                    response = await request(`${urlBase}/api/user/createAttachment`, {
                        method: "POST",
                        credentials: 'include',
                        body: formData
                    })

                    if (!response?.success) {
                        throw new Error(
                            response.error ??
                            response.message ??
                            "Erro interno do servidor"
                        );
                    }

                    //Bloqueia inputs selecionados como atestado
                    periodo.forEach(data => {
                        let containerData = document.querySelector(`[data-date="${data}"]`)
                        let inputContainer = containerData.querySelectorAll('.horario-input');
                        let containerBts = containerData.querySelector('.items-botoes');

                        certificateButtonCalendar(containerBts);

                        inputContainer.forEach(input => {
                            setReadonly(input)

                        })

                    })

                    //Apresenta modal
                    showModalToast(response.data);

                } catch (error) {
                    showModalToast(error, "error");
                }


            }
            break;

        //==========================================================================

        //==================== Caso de deleta de registro ==========================  

        case 'delete':

            //Apresenta modal Para remover horario
            resultado = await apresentarModal(
                'modal-default',
                'alerta',
                `Deseja Excluir o registro de ${card.dataset.date}`
            );

            //Caso clique no botão execulta
            if (resultado) {

                //Seleciona rota de exclusão
                try {
                    response = await request(`${urlBase}/api/user/attendance/delete`, {
                        method: "DELETE",
                        credentials: 'include',
                        body: {
                            date: card.dataset.date
                        }
                    })

                    if (!response?.success) {
                        throw new Error(
                            response.error ??
                            response.message ??
                            "Erro interno do servidor"
                        );
                    }

                    //Apresenta modal
                    showModalToast(response.data);

                    //Remove estilos e dados
                    inputs.forEach(input => {
                        RemoveReadonly(input)
                        input.value = ""
                    });

                    //Reabilita botão de submit
                    const oldBtns = buttonSubmitCalendar(card.querySelector('.items-botoes'));

                    card.appendChild(oldBtns)


                } catch (error) {
                    showModalToast(error, "error");
                }
            }

            break;

        //==================== Caso de abrir Atestado ==========================  

        case 'open-certificate':
            const link = btn.dataset.certificate;

            window.open(link, "_blank");
            break

        //==========================================================================

        //==================== Caso de deleta de Atestado ==========================  

        case 'delete-certificate':

            const mensagem = `<span>Deseja Excluir o Atestado da data ${card.dataset.date}</span>
                                    <br>
                                    <small style="color:red">Essa ação afetarar outras datas vinculada</small>
                                    `
            //Apresenta modal Para remover horario
            resultado = await apresentarModal(
                'modal-default',
                'alerta',
                mensagem
            );

            //Caso clique no botão execulta
            if (resultado) {

                const idCertificate = card.dataset.certificate;

                //Seleciona rota de exclusão
                try {
                    response = await request(`${urlBase}/api/user/deleteAttachment`, {
                        method: "DELETE",
                        credentials: 'include',
                        body: idCertificate
                    })

                    if (!response?.success) {
                        throw new Error(
                            response.error ??
                            response.message ??
                            "Erro interno do servidor"
                        );
                    }

                    //Coleta todos os cards que possui o id do certificado
                    const cardsWithId = document.querySelectorAll(`[data-certificate='${idCertificate}']`)

                    cardsWithId.forEach(card => {
                        const containerBtns = card.querySelector('.items-botoes');

                        if (containerBtns) {
                            buttonSubmitCalendar(containerBtns);
                        }

                        const allInputsCard = card.querySelectorAll('.horario-input');
                        //Remove estilos e dados
                        allInputsCard.forEach(input => {
                            RemoveReadonly(input)
                            input.value = ""
                        });
                    })

                    //Apresenta modal
                    showModalToast(response.data);

                } catch (error) {
                    showModalToast(error, "error");
                }
            }
            break;

        //==========================================================================

    }

});





