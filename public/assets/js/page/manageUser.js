import { request } from "../service/ajax.js";
import { apresentarModal } from "../utils/modal.js";
import { showLoading, hideLoading } from "../utils/loading.js";
import { debouncePromise } from "../utils/delay.js";
import { getFormData } from "../utils/form.js";
import { createOptions } from "../utils/createoptions.js";

//Coleta setores Registrados no sistema
let optionSetor
let optionUser
try {
    let response
    response = await request(`${urlBase}/api/user/sectors`)

    if (!response || response.success != true) {
        throw new Error(response?.message || "Erro interno no servidor");
    }
    optionSetor = response.data

} catch (error) {
    console.log("Erro de comunicação")
}

//Gera options para selectSetor
const selectSetor = document.querySelector('#dropdown-setor')
const selectUser = document.querySelector('#dropdown-pessoas')
const containerUsers = document.querySelector('#container-pessoas')
createOptions(selectSetor, optionSetor)

//Selecionar setor selecionado
selectSetor.addEventListener('change', async (event) => {
    //Libera container users
    containerUsers.style.display = "flex";
    //Pega Valor da Seleção escolhida
    const valorSelecionado = event.target.value;

    //Tempo debounce para impedir varias requisições
    await debouncePromise(500);
    selectUser.innerHTML = "";

    try {
        let response
        response = await request(`${urlBase}/api/user/sector/users?setor=${valorSelecionado}`)

        if (!response || response.success != true) {
            throw new Error(response?.message || "Erro interno no servidor");
        }
        
        optionUser = response.data
        //Gera options para selectUser
        createOptions(selectUser, optionUser)

    } catch (error) {
        console.log("Erro de comunicação")
    }
})




