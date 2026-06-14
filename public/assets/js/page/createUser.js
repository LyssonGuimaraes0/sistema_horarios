import { request } from "../service/ajax.js";
import { apresentarModal } from "../utils/modal.js";
import { showLoading, hideLoading } from "../utils/loading.js";
import { delay } from "../utils/delay.js";
import { getFormData } from "../utils/form.js";
import { formatTextToCPF, formatTextToString, formatTextToStringWithNumber } from "../utils/format.js";

//Manipulação de inputs

const inputs = document.querySelectorAll('input')

//Separa inputPadrão de inpuntCPF
const inputStrings = [...inputs].filter(input => input.id !== 'cpf')
const inputCPF = [...inputs].find(input => input.id === 'cpf');
const inputNome = [...inputs].find(input => input.id === 'nome_completo');
const inputUserName = [...inputs].find(input => input.id === 'username');
const inputSenha = [...inputs].find(input => input.id === 'senha');
const inputSenhaConfirmar = [...inputs].find(input => input.id === 'senha-confirmar');

//evento de clique para todos inputs
inputStrings.forEach(input => {
    input.addEventListener('input', (e) => {
        let valor = formatTextToStringWithNumber(e.target.value);
        e.target.value = valor;
    });
});

//evento de clique para CPF
inputCPF.addEventListener('input', (e) => {
    let valor = formatTextToCPF(e.target.value);
    e.target.value = valor;
});

//Evento para  de nome 
inputNome.addEventListener('input', (e) => {
    let valor = formatTextToString(e.target.value);
    e.target.value = valor;
    const palavras = e.target.value.trim().split(/\s+/);

    inputUserName.value = palavras
        .slice(0, 2)
        .join('')
        .toLowerCase();
});

//Evento para confirmar senha que usuario digitou
function validarSenha() {
    if (inputSenha.value !== inputSenhaConfirmar.value) {
        inputSenhaConfirmar.style.border = "2px solid red";
    } else {
        inputSenhaConfirmar.style.border = "";
    }
}

inputSenha.addEventListener('input', validarSenha);
inputSenhaConfirmar.addEventListener('input', validarSenha);


//Coleta de formulario
const formCreateUser = document.querySelector('#form-create-user')

//Configuração do botão do formulario
const btnLogin = document.querySelector('.btn-login');

formCreateUser.addEventListener('submit', async (event) => {
    event.preventDefault();

    if (inputSenha.value !== inputSenhaConfirmar.value) {

        inputSenhaConfirmar.style.border = "2px solid red";

        await apresentarModal("modal-default", "falha", "As senhas não coincidem.");

        return;
    }

    const checkboxAdmin = document.querySelector('#checkbox');

    //Coleta dados do formulario
    const dados = getFormData(event.target);

    //Seta dados de checkbox
    dados.permissao = checkboxAdmin.checked ? true : false;
    delete dados["senha-confirmar"]

    console.log(dados);


})





