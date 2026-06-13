import { request } from "../service/ajax.js";
import { getFormData } from "../utils/form.js";
import { delay } from "../utils/delay.js";

//Coleta de formulario
const formLogin = document.querySelector('#form-login')

//Configuração do botão do formulario
const btnLogin = document.querySelector('.btn-login');
const animationLoading = btnLogin.querySelector('.loading');
const mngsError = document.querySelector('.error-mensagem')

formLogin.addEventListener('submit', async (event) => {
    event.preventDefault();

    //Garante mensagem de erro seja limpa caso usuario tente loga novamente

    mngsError.style.visibility = 'hidden';

    if (btnLogin.lastChild && btnLogin.lastChild.nodeType === Node.TEXT_NODE) {
        btnLogin.lastChild.textContent = "";
    }

    animationLoading.style.display = 'block';

    await delay(900);

    //Coleta dados do formulario
    const dados = getFormData(event.target);
    let response

    //Chamada da API para AuthLoginController
    try {
        response = await request('http://localhost/projetos_pessoais/sistema-de-horarios-mvc/api/auth/login', {
            method: 'POST',
            credentials: 'include',
            body: {
                username: dados.usuario,
                password: dados.password
            }

        });

        if (!response || response.success != true) {
            throw new Error(response?.error);
        }

        //Login conseguiu ser cadastrado
        window.location.href = "./user/dashboard";

    } catch (error) {
        mngsError.textContent = "Email ou Senha incorreta! Tente Novamente"
        mngsError.style.visibility = 'visible'
    } finally {
        animationLoading.style.display = 'none';
        if (btnLogin.lastChild && btnLogin.lastChild.nodeType === Node.TEXT_NODE) {
            btnLogin.lastChild.textContent = "Login";
        }

    }

})

