import { request } from "../service/ajax.js";
import { apresentarModal } from "../utils/modal.js";
import { createOptions } from "../utils/createoptions.js";
import { showLoading, hideLoading } from "../utils/loading.js";
import { delay } from "../utils/delay.js";

//Carrega Meses e ano validos
let years;
let months
try {
    let response
    response = await request('../api/attendance/available-periods')

    if (!response || response.success != true) {
        throw new Error(response?.error);
    }

    //Separa variaveis de ano e mes
    console.log(response)
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

btnMes.addEventListener('click', async function () {

    //Coleta dados de Selecionados pelo usuario
    let valorMes = parseInt(selectMes.value, 10);
    let valorAno = parseInt(selectAno.value, 10);

    console.log(valorMes)
    console.log(valorAno)

    //Buscar meses selecionado pelo usuario
    try {
        let response
        response = await request(`../api/attendance/calendar/${valorAno}/${valorMes}`)

        if (!response || response.success != true) {
            throw new Error(response?.error);
        }

        //Separa variaveis de ano e mes
        console.log(response)

    } catch (error) {
        console.log("Erro de comunicação")
    }



    //Libera container
    containerCalendario.style.display = "block";
    //Toca animação
    showLoading(containerCalendario);
    await delay(1000);
    hideLoading(containerCalendario)



})






