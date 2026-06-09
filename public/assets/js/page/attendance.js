import { request } from "../service/ajax.js";
import { apresentarModal } from "../utils/modal.js";
import { createOptions } from "../utils/createoptions.js";

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

btnMes.addEventListener('click', function(){
    containerCalendario.style.display = "block";
})






