import { request } from "../service/ajax.js";
import {showModalToast} from "../utils/modal.js";
import { createOptions } from "../utils/createoptions.js";

//Libera containers de feriado e ponto facultativo

const containerFeriado = document.querySelector('#container-feriado')
const containerPontoFacultativo = document.querySelector('#container-ponto-facultativo')
const select = document.querySelector('.dropdown')

//sessões dados
const selectFeriado = document.querySelector('#nome-feriado')

let listFeriado

//Solicita dados de feriados
try {
    let response
    response = await request(`${urlBase}/api/holiday`)

    if (!response || response.success != true) {
        throw new Error(response?.error);
    }

    listFeriado = response.data;
    console.log(listFeriado)

} catch (error) {
    showModalToast(error, "error");
}

//Montar select de Feriados
createOptions(selectFeriado, listFeriado)


function switchContainer(containerAtive, containerDisable) {
    containerDisable.style.display = "none"
    containerAtive.style.display = "block";
}

//Muda container pelo selector
select.addEventListener('change', async () => {
    const valor = Number(select.value);
    console.log(valor)

    switch (valor) {
        case 1:
            switchContainer(containerFeriado, containerPontoFacultativo)
            break;

        case 2:
            switchContainer(containerPontoFacultativo, containerFeriado)
            break;

    }
});