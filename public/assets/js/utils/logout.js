import { request } from "../service/ajax.js";

//Função para deslogar da pagina
async function logout() {
    let response
        response = await request('http://localhost/projetos_pessoais/sistema-de-horarios-mvc/api/auth/logout', {
            method: 'POST',
            credentials: 'include'
        });

        /* if (!response || response.success != true) {
            throw new Error(response?.error);
        } */

        //Login conseguiu ser cadastrado
        window.location.href = "/projetos_pessoais/sistema-de-horarios-mvc/";

}

//Verifica click
const btnLogout = document.querySelector('#btn-logout');
btnLogout.addEventListener('click', logout);