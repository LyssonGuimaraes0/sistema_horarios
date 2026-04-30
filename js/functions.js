//Função Padrão de Inputs

function Verificar_inputs(input, index, inputHorarios) {

    let ValorAtual = input.value
    let ValorProximo = null
    let ValorAnterior = null


    if (inputHorarios[index + 1]) {
        ValorProximo = inputHorarios[index + 1].value

    }

    if (inputHorarios[index - 1]) {
        ValorAnterior = inputHorarios[index - 1].value
    }

    if (ValorAnterior && ValorAtual < ValorAnterior) {
        validacao_campo(input, true)

    } else if (ValorProximo && ValorAtual > ValorProximo) {
        validacao_campo(input, true)

    } else {
        validacao_campo(input, false)
    }

    return { ValorAtual, ValorAnterior, ValorProximo };
}

function validacao_campo(input, resposta) {
    if (resposta) {
        input.value = ""
        input.style.border = "2px solid red"
    } else {
        input.style.border = ""
    }

}

//Fecha modal
function fechar_modal() {
    document.querySelectorAll('.modal-background').forEach(modal => {
        modal.style.display = 'none';
    });
}


//Apresentação de Modail

function apresenta_modal(condicao, mensagem,) {


    var TituloModal = document.createElement('span');
    var DescricaoModal = document.createElement('p');
    DescricaoModal.innerHTML = mensagem;

    switch (condicao) {
        case 'sucesso':

            var cor = "#14dd57"
            TituloModal.innerHTML = "<i class='fa-solid fa-circle-check'></i> Sucesso!";
            conf_modal(TituloModal, DescricaoModal, cor);
            break;

        case 'falha':

            var cor = "#f03210ff"
            TituloModal.innerHTML = "<i class='fa-solid fa-circle-xmark'></i> Falha!";
            conf_modal(TituloModal, DescricaoModal, cor);

            break;

        case 'alerta':

            var cor = "#f07c10ff"
            TituloModal.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Alerta!";
            conf_modal(TituloModal, DescricaoModal, cor);

            break;

        default:
            break;
    }

    function conf_modal(titulo, descricao, cor, funcao = null) {
        //Coleta Informações do formulario
        const ModalBackground = document.querySelector('.modal-background');
        const ModalContainer = document.querySelector('.modal-container');
        const ModalCabecalho = document.querySelector('.modal-cabecalho');
        const ModalDescricao = document.querySelector('.modal-descricao');
        const ModalBtn = document.querySelector('.btn-modal');
        //botao cancelar
        const ModalBtnCancelar = document.querySelector('#botao-cancelar');
        //botao okay ou confirmar
        const ModalBtncConfirmar = document.querySelector('#botao-confirmar');

        //Limpa o modal
        ModalCabecalho.innerHTML = "";
        ModalDescricao.innerHTML = "";

        //Monta modal
        ModalContainer.style.border = `2px solid ${cor}`
        titulo.style.color = cor;
        ModalCabecalho.appendChild(titulo);
        ModalDescricao.appendChild(descricao)

        //Apresnta o modal
        ModalBackground.style.display = "flex";


        ModalBtn.onclick = () => {
            fechar_modal();
        };



    }

}
