import { request } from "../service/ajax.js";

//Coleta de formulario
const formLogin = document.querySelector('#form-login')

formLogin.addEventListener('submit', async (event) => {
    event.preventDefault();
    //Coleta dados do formulario
    const form = new FormData(formLogin);

    const data = {
        username: form.get('usuario'),
        password: form.get('password')
    };


    //Chamada da API para AuthLoginController
    let response = await request('http://localhost/projetos_pessoais/sistema-de-horarios-mvc/api/v1/auth/login', {
    method: 'POST',
    body: {
        username: data.username,
        password: data.password
    }
});

console.log(response);


})

