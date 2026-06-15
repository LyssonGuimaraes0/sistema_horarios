import { request } from "../service/ajax.js";
import { apresentarModal } from "../utils/modal.js";

//Coleta de dados do usuario
const homeNomeUser = document.querySelector('#home-nameUser')
try {
    let response
    response = await request(`${urlBase}/api/user`)

    if (!response || response.success != true) {
        throw new Error(response?.error);
    }

    //Apresenta dados de Usuario
    homeNomeUser.textContent += response.data.nome

} catch (error) {
    console.log("Erro de comunicação")
}





