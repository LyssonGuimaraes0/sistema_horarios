import { request } from "../service/ajax.js";

import {
    showModalToast,
    apresentarModal
} from "../utils/modal.js";

import { createOptions } from "../utils/createoptions.js";
import { formatDate } from "../utils/date.js";
import { getFormData } from "../utils/form.js";

//Libera containers de feriado e ponto facultativo

const containerFeriado = document.querySelector('#container-feriado')
const containerPontoFacultativo = document.querySelector('#container-ponto-facultativo')
const select = document.querySelector('.dropdown')

//sessões dados
const selectFeriado = document.querySelector('#nome-feriado')

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

//Montar select de Feriados
createOptions(selectFeriado, arrayHoliday)

//Montar estrutura accordin
const ulHolidays = document.querySelector('.lista-feriados')

arrayHoliday.forEach(holiday => {
    const item = document.createElement('li')
    item.textContent = holiday

    ulHolidays.appendChild(item);
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

//Página de Gereciar Feriado
const formCreateHoliday = document.querySelector('#form-create-holiday')
const mngsError = document.querySelector('.error-mensagem')

formCreateHoliday.addEventListener('submit', async (e) => {
    e.preventDefault();

    mngsError.style.visibility = 'hidden';

    let dados = getFormData(formCreateHoliday);

    console.log(dados);

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


