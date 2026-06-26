import { request } from "../service/ajax.js";

import {
    apresentarModal,
    showModalCertificate,
    showModalToast,
    showModalTimeSheet
} from "../utils/modal.js";

import { createOptions } from "../utils/createoptions.js";
import { showLoading, hideLoading } from "../utils/loading.js";
import { delay } from "../utils/delay.js";

import {
    createCardList,
    createButtonsCalendar,
    alterButtonsCalendar,
    setReadonly,
    RemoveReadonly,
    buttonSubmitCalendar,
    certificateButtonCalendar
} from "../utils/card.js";

import { verificarInputs, } from "../utils/verifyInput.js";
import { gerarPeriodo } from "../utils/date.js";

//Carrega Meses e ano validos
let years;
let months
try {
    let response
    response = await request(`${urlBase}/api/attendance/available-periods`)

    if (!response || response.success != true) {
        throw new Error(response?.error);
    }

    //Separa variaveis de ano e mes
    years = response.data.years;
    months = response.data.months;

} catch (error) {
    console.log("Erro de comunicação")
}

//Cria lista de meses e anos
const selectMes = document.querySelector('#selectMes')
const selectAno = document.querySelector('#selectAno')

createOptions(selectMes, months)
createOptions(selectAno, years)

//Verificar clique de botão de carregar
const btnMes = document.querySelector('#abrir-calendario')
const containerCalendario = document.querySelector('#calendario-form')
const templateIpunt = document.querySelector('#input-Horarios')

//Container de cards de calendairo
const containerForm = document.querySelector('#attendance-form')

//Botões do header
const btnGerarFrequencia = document.querySelector('#btn-gerar-frequencia')
const btnAnexarFrequencia = document.querySelector('#btn-anexar-frequencia');

//Verificar clique em botão de anexar frequencia.
btnAnexarFrequencia.addEventListener('click', function () {
    showModalTimeSheet();
})

//Gerar PDF em botão de Gerar frequencia.
btnGerarFrequencia.addEventListener('click', function () {
    //Coleta dados de Selecionados pelo usuario
    let valorMes = parseInt(selectMes.value, 10);
    let valorAno = parseInt(selectAno.value, 10);
    try {
        //Abrir PDF do mes correspondente
        window.open(
            `${urlBase}/api/attendance/report?year=${valorAno}&month=${valorMes}`, '_blank'
        );
    } catch (error) {
       showModalToast(error, "error"); 
    }

})



let response;

let carregando = false;

btnMes.addEventListener('click', async function () {

    if (carregando) return;

    carregando = true;
    btnMes.disabled = true;

    //Libera container
    containerCalendario.style.display = "block";
    //Toca animação
    showLoading();

    try {

        if (containerForm.innerHTML.trim() != "") {
            containerForm.innerHTML = "";
        }
        //Bloqueia rolagem da página
        containerCalendario.style.overflow = "hidden";

        //Coleta dados de Selecionados pelo usuario
        let valorMes = parseInt(selectMes.value, 10);
        let valorAno = parseInt(selectAno.value, 10);

        //Pega nome do mes
        let nomeMes = selectMes.querySelector(`option[value="${valorMes}"]`).textContent

        //Buscar meses selecionado pelo usuario

        response = await request(`${urlBase}/api/attendance/calendar/${valorAno}/${valorMes}`)

        if (!response || response.success != true) {
            throw new Error(response?.error);
        }


        //Cria card para cada elemento

        await delay(850)

        response.data.forEach(item => {
            //Organização de variavel
            const dadosData =
            {
                month: nomeMes,
                year: valorAno,
                date: item[0].date,
                weekName: item[0].weekName,
                weekend: item[0].weekend,
                holiday: item.feriado,
                attendance: item.attendance,
                certificate: item.certificate
            }


            const card = createCardList(templateIpunt, dadosData)

            containerForm.appendChild(card)

        });

        await delay(1500);
        hideLoading();
        //Libera rolagem depois de toca a animação
        containerCalendario.style.overflow = "auto";

    } catch (error) {
        console.log("Erro de comunicação")
        //Para a execução
        return;

    } finally {
        carregando = false;
        btnMes.disabled = false;
    }


})

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
containerForm.addEventListener('click', async (e) => {
    e.preventDefault()
    const btn = e.target.closest('.botao-calendario, .btn-calendario');
    if (!btn) return;

    const card = btn.closest('.container-horarios');
    const action = btn.dataset.action;

    //Coleta todos os inputs do card
    const inputs = card.querySelectorAll('.horario-input')

    let attendanceData = {}
    let inputsattendance = {};

    //Casos possiveis com botões presente no calendario
    switch (action) {
        //Enviar dados de formulario

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

                console.log(resultadoCertificate)

                //Converte dados para form

                const formData = new FormData();
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
            const resultado = await apresentarModal(
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

        //==========================================================================

    }
});







