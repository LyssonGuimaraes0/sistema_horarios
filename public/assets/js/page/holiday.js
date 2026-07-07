import { request } from "../service/ajax.js";

import {
    showModalToast,
    apresentarModal
} from "../utils/modal.js";

import { delay } from "../utils/delay.js";

import { showLoading, hideLoading } from "../utils/loading.js";

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

let arrayFeriado = []
let arrayPontoFacultativo = []

//Organizar em array para
Object.entries(listHoliday).map(([data, nome]) => {
    //Separa dados de Feriados e Ponto facultativo

    const infoFeriado = {
        data: data,
        nome: nome
    };

    if (nome == "Ponto Facultativo") {
        arrayPontoFacultativo.push(infoFeriado)
    } else {
        arrayFeriado.push(infoFeriado)
    }
})

//Função para setar listas
function criarItemLista(template, itemFeriado) {
    const clone = template.content.cloneNode(true);
    const spanName = clone.querySelector('span');
    const icon = clone.querySelector('i');

    spanName.textContent = `${itemFeriado.nome} - ${formatDate(itemFeriado.data)}`;

    if (icon) {
        icon.dataset.date = itemFeriado.data;
    }

    return clone; // Retorna o elemento pronto para ser inserido na UL
}


//Montar estrutura accordins
const ulHolidays = containerFeriado.querySelector('.lista-feriados')
const ulPontoFacultativo = containerPontoFacultativo.querySelector('.lista-feriados')
const template = document.querySelector('#item-feriado')

// Renderiza os Feriados
arrayFeriado.forEach(item => {
    const itemPronto = criarItemLista(template, item);
    ulHolidays.appendChild(itemPronto);
});

// Renderiza os Pontos Facultativos
arrayPontoFacultativo.forEach(item => {
    const itemPronto = criarItemLista(template, item);
    ulPontoFacultativo.appendChild(itemPronto);
});

//Analizar Clique do accordin
const accordinHoliday = containerFeriado.querySelector('.accordion-header')
const accordinPontoFacultativo = containerPontoFacultativo.querySelector('.accordion-header')

accordinHoliday.addEventListener('click', function () {
    containerFeriado.querySelector('.accordion-item').classList.toggle('active')
})

accordinPontoFacultativo.addEventListener('click', function () {
    containerPontoFacultativo.querySelector('.accordion-item').classList.toggle('active')
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

// Verifica cliques de icons remover feriados (captura em elementos do template)
document.addEventListener('click', async (e) => {
    const icone = e.target.closest('.icon-deletar');

    if (!icone) return;

    const data = icone.dataset.date;
    const paiElemento = icone.closest('li');
    const texto = paiElemento.querySelector('span').textContent;

    const resultado = await apresentarModal(
        'modal-default',
        'alerta',
        `Deseja Excluir o registro de ${texto}`
    );

    if (resultado) {

        try {
            response = await request(`${urlBase}/api/holiday/delete`, {
                method: "DELETE",
                credentials: 'include',
                body: data
            })

            if (!response?.success) {
                throw new Error(
                    response.error ??
                    response.message ??
                    "Erro interno do servidor"
                );
            }

            //Apaga elemento
            if (paiElemento) {
                paiElemento.remove();
            }

            //Apresenta modal de confirmação
            await apresentarModal(
                'modal-default',
                'sucesso',
                response.data
            );

        } catch (error) {

            //Apresenta modal de falha

            apresentarModal(
                'modal-default',
                'falha',
                error
            );
        }


    }


});





//Gerar Ponto Facultativo
formCreatePontoFacultativo.addEventListener('submit', async (e) => {
    e.preventDefault();

    let loading = formCreatePontoFacultativo.querySelector('.loading-overlay')

    //Inicia animação
    showLoading(loading);

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

    try {
        response = await request(`${urlBase}/api/optional-holidays/create`, {
            method: "POST",
            credentials: 'include',
            body: dados
        });

        if (!response?.success) {
            throw new Error(
                response.error ??
                response.message ??
                "Erro interno do servidor"
            );
        }

        await delay(800);
        hideLoading();

        //Apresenta modal de confirmação
        await apresentarModal(
            'modal-default',
            'sucesso',
            response.data
        );

        formCreatePontoFacultativo.reset()

    } catch (error) {
        await delay(300);
        await hideLoading(loading);
        //Apresenta modal de falha

        apresentarModal(
            'modal-default',
            'falha',
            error
        );
    }

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
            response.data
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


