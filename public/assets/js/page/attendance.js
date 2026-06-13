import { request } from "../service/ajax.js";
import { apresentarModal } from "../utils/modal.js";
import { createOptions } from "../utils/createoptions.js";
import { showLoading, hideLoading } from "../utils/loading.js";
import { delay } from "../utils/delay.js";
import { createCardList } from "../utils/card.js";

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

//Container de cards de calendairo
const containerForm = document.querySelector('#attendance-form')

let response;

btnMes.addEventListener('click', async function () {
    console.log('click agr')

    if (containerForm.innerHTML.trim() != "") {
        containerForm.innerHTML = "";
        showLoading(containerCalendario);
    }


    //Coleta dados de Selecionados pelo usuario
    let valorMes = parseInt(selectMes.value, 10);
    let valorAno = parseInt(selectAno.value, 10);

    //Buscar meses selecionado pelo usuario
    try {
        response = await request(`../api/attendance/calendar/${valorAno}/${valorMes}`)

        if (!response || response.success != true) {
            throw new Error(response?.error);
        }

    } catch (error) {
        console.log("Erro de comunicação")
    }


    //Cria cards para cada dado
    //Libera container
    containerCalendario.style.display = "block";
    //Toca animação
    showLoading(containerCalendario);

    //Cria card para cada elemento

    await delay(900)


    response.data.forEach(item => {
            
            //
            const dadosData = 
                {
                    date: item[0].date,
                    weekName: item[0].weekName,
                    weekend : item[0].weekend,
                    holiday : item.feriado
                }
    
            const card = createCardList(templateIpunt, dadosData)
    
            containerForm.appendChild(card)
    
        }); 

    hideLoading(containerCalendario)


    /*  response.data.forEach(item => {
                console.log(item[0].date);
                console.log(item[0].weekName);
                console.log(item[0].weekend); 
                console.log(item.feriado);
                console.log(item.attendance);
            }); */




})






