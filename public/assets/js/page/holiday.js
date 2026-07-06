import { request } from "../service/ajax.js";

import {
    showModalToast,
    apresentarModal
} from "../utils/modal.js";

import { formatDate } from "../utils/date.js";
import { getFormData } from "../utils/form.js";

import { verificarInputs, } from "../utils/verifyInput.js";

//Libera containers de feriado e ponto facultativo

const containerFeriado = document.querySelector('#container-feriado')
const containerPontoFacultativo = document.querySelector('#container-ponto-facultativo')
const select = document.querySelector('.dropdown')

let listHoliday
let response

//Solicita dados de feriados
try {

    response = await request(`${urlBase}/api/holiday`)

    if (!response || response.success != true) {
        throw new Error(response?.error);
    }

    listHoliday = response.data;

} catch (error) {
    showModalToast(error, "error");
}

//Organizar em array para
const arrayHoliday = Object.entries(listHoliday).map(([data, nome]) => {
    return `${nome} - ${formatDate(data)}`
})

//Montar estrutura accordin
const ulHolidays = document.querySelector('.lista-feriados')
const template = document.querySelector('#item-feriado')

arrayHoliday.forEach(holiday => {
    const clone = template.content.cloneNode(true);
    const nameHoliday = clone.querySelector('span');
    nameHoliday.textContent = holiday
    ulHolidays.appendChild(clone);
})

//Analizar Clique do accordin
const accordin = document.querySelector('.accordion-header')
accordin.addEventListener('click', function () {
    document.querySelector('.accordion-item').classList.toggle('active')
})


function switchContainer(containerAtive, containerDisable) {
    containerDisable.style.display = "none"
    containerAtive.style.display = "block";
}

//Muda container pelo selector
select.addEventListener('change', async () => {
    const valor = Number(select.value);

    switch (valor) {
        case 1:
            switchContainer(containerFeriado, containerPontoFacultativo)
            break;

        case 2:
            switchContainer(containerPontoFacultativo, containerFeriado)
            break;

    }
});

const formCreatePontoFacultativo = document.querySelector('#form-create-ponto-facultativo')
const formCreateHoliday = document.querySelector('#form-create-holiday')
const mngsError = document.querySelector('.error-mensagem')

//Coleta containers de horarios e verifica valores digitados
const containersPontoFacultativo = formCreatePontoFacultativo.querySelectorAll('.container-holiday')

document.addEventListener('input', function (e) {
    //Retorna caso não seja inputs de horario
    if (!e.target.classList.contains('horario-input') || e.target.name === "date") return;

    let container

    const input = e.target;
    //Verifica caso seja input de data ou horarios
    if (input.type === 'date') {
        container = input.closest('#container-date');
        let facultativo = document.querySelector('[name="date"]')

        //Verifica se o valor é menor que data ponto facultativo
        if (input.value <= facultativo.value) {
            input.value = "";
            return;
        }

    } else {
        //Verifica caso seja estagiario
        if (container?.dataset.tipo === 'estagiario') {
            container = input.closest('.item-ponto-facultativo');
        } else {
            container = input.closest('.container-holiday');
        }
    }
    let inputs;
    inputs = [...container.querySelectorAll('.horario-input')];

    const index = inputs.indexOf(input);

    verificarInputs(input, index, inputs)

});

//Gerar Ponto Facultativo
formCreatePontoFacultativo.addEventListener('submit', async (e) => {
    e.preventDefault();

    let dados = {
        time: {}
    };

    //Armazenar Datas
    const inputsData = formCreatePontoFacultativo.querySelectorAll('input[type="date"]')

    inputsData.forEach(input => {
        dados[input.name] = input.value;
    });

    //Armazenar horarios
    containersPontoFacultativo.forEach(container => {
        const tipo = container.dataset.tipo

        dados.time[tipo] = {}

        container.querySelectorAll('input').forEach(input => {
            dados.time[tipo][input.name] = input.value;
        });
    });

    console.log(dados);





})


//Página de Gereciar Feriado
formCreateHoliday.addEventListener('submit', async (e) => {
    e.preventDefault();

    mngsError.style.visibility = 'hidden';

    let dados = getFormData(formCreateHoliday);


    if (listHoliday[dados["data-feriado"]]) {
        mngsError.textContent = "Data do feriado já esta cadastrado"
        mngsError.style.visibility = 'visible'
        return;
    }
    try {
        response = await request(`${urlBase}/api/holiday/create`, {
            method: 'POST',
            credentials: 'include',
            body: dados
        })

        if (!response?.success) {
            throw new Error(
                response.error ??
                response.message ??
                "Erro interno do servidor"
            );
        }

        //Apresenta modal de confirmação
        await apresentarModal(
            'modal-default',
            'sucesso',
            `O Feriado foi registrado!`
        );

        formCreateHoliday.reset()
        window.location.reload();


    } catch (error) {
        //Apresenta modal de confirmação
        apresentarModal(
            'modal-default',
            'falha',
            error
        );
    }


})


