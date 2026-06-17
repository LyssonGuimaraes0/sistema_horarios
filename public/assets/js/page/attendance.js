import { request } from "../service/ajax.js";
import { apresentarModal } from "../utils/modal.js";
import { createOptions } from "../utils/createoptions.js";
import { showLoading, hideLoading } from "../utils/loading.js";
import { delay } from "../utils/delay.js";
import { createCardList, createButtonsCalendar } from "../utils/card.js";
import { verificarInputs } from "../utils/verifyInput.js";
import { showModalToast } from "../utils/modal.js";

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

let response;

let carregando = false;

btnMes.addEventListener('click', async function () {

    if (carregando) return;

    carregando = true;
    btnMes.disabled = true;


    try {



        if (containerForm.innerHTML.trim() != "") {
            containerForm.innerHTML = "";
        }

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


        //Cria cards para cada dado
        //Libera container
        containerCalendario.style.display = "block";
        //Toca animação
        showLoading();

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
                attendance: item.attendance
            }

            const card = createCardList(templateIpunt, dadosData)

            containerForm.appendChild(card)

        });

        await delay(1500);
        hideLoading();


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


//Valida Cliques de botões gerados no formulario

containerForm.addEventListener('click', async (e) => {
    e.preventDefault()
    const btn = e.target.closest('.botao-calendario, .btn-calendario');
    if (!btn) return;

    const card = btn.closest('.container-horarios');
    const action = btn.dataset.action;
    let attendanceData = {}
    let inputsattendance = {};
    //Casos possiveis com botões presente no calendario
    switch (action) {
        //Enviar dados de formulario

        case 'submit':

            //Coleta dados dos inputs
            const inputs = card.querySelectorAll('.horario-input')
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
                    input.setAttribute("readonly", "true")
                });

                //Altera botões do container btn
                createButtonsCalendar(card.querySelector('.items-botoes'));

                //Apresenta modal
                showModalToast(response.data);


            } catch (error) {
                showModalToast(error, "error");
            }



            break;

        //Editar dados de formulario    
        case 'edit':
            console.log('Editar', card.dataset.date);
            break;

        //Adicionar atestado por periodo
        case 'certificate':
            console.log('Certificate', card.dataset.date);
            break;

        case 'delete':

            //Apresenta modal Para remover horario
            const resultado = await apresentarModal(
                'modal-default',
                'alerta',
                `Deseja Excluir o registro de ${card.dataset.date}`
            );

            if (resultado) {
                //Coleta resultado solicitar limpeza

            } else {

            }

            break;

        case 'confirm':
            console.log('Confirmar', card.dataset.date);
            break;
    }
});







