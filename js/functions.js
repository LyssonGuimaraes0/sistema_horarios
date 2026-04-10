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


//Apresentação de Modail

function apresenta_modal(condicao, mensagem,) {

    //Fecha modal
    function fechar_modal() {
        document.querySelectorAll('.modal-background').forEach(modal => {
            modal.style.display = 'none';
        });
    }

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

// == Adicionar Input de ponto facultativo ====================================================

let contador = 0;
const divInputs = document.querySelector('.container-input')

function adicionar_input() {
    contador++;
    const novaDivInputs = divInputs.cloneNode(true);

    novaDivInputs.id = `container-inputs-${contador}`

    //Bloqueia containers Servidor e Funcionario

    const NovoSelecaoTipoFuncionario = novaDivInputs.querySelector('#selecao-tipo-funcionario')
    NovoSelecaoTipoFuncionario.id = `selecao-tipo-funcionario-[${contador}]`
    NovoSelecaoTipoFuncionario.name = `selecao-tipo-funcionario[${contador}]`;

    const NovoContainerServidorPublico = novaDivInputs.querySelector('.container-servidor-publico')
    NovoContainerServidorPublico.id = `container-servidor-publico-[${contador}]`

    const NovoContainerEstagiario = novaDivInputs.querySelector('.container-estagiario')
    NovoContainerEstagiario.id = `container-estagiario-[${contador}]`

    LiberaContainer(NovoSelecaoTipoFuncionario, NovoContainerServidorPublico, NovoContainerEstagiario);

    //Cria o novos elementos e adicionar o contador ao arrays

    //Calendarios de data
    const calendarioInicio = novaDivInputs.querySelector('#horairo-inicio')
    calendarioInicio.name = `inicio-ponto-facultativo[${contador}]`

    const calendariosaida = novaDivInputs.querySelector('#horairo-fim')
    calendariosaida.name = `fim-ponto-facultativo[${contador}]`

    //Inputs de horario

    //===============Servidor Publico================
    const servidorPublicoEntrada = novaDivInputs.querySelector('#servidor-publico-entrada')
    servidorPublicoEntrada.name = `servidor-publico-entrada[${contador}]`
    const servidorPublicoSaidaAlmoco = novaDivInputs.querySelector('#servidor-publico-saida-almoco')
    servidorPublicoSaidaAlmoco.name = `servidor-publico-saida-almoco[${contador}]`
    const servidorPublicoVoltaAlmoco = novaDivInputs.querySelector('#servidor-publico-volta-almoco')
    servidorPublicoVoltaAlmoco.name = `servidor-publico-volta-almoco[${contador}]`
    const servidorPublicoSaida = novaDivInputs.querySelector('#servidor-publico-saida')
    servidorPublicoSaida.name = `servidor-publico-saida[${contador}]`
    //================================================

    //===============Estagiario Manha================
    const estagiarioManhaEntrada = novaDivInputs.querySelector('#estagiario-manha-entrada')
    estagiarioManhaEntrada.name = `estagiario-manha-entrada[${contador}]`
    const estagiarioManhaSaida = novaDivInputs.querySelector('#estagiario-manha-saida')
    estagiarioManhaSaida.name = `estagiario-manha-saida[${contador}]`
    //================================================

    //===============Estagiario Tarde================
    const estagiarioTardeEntrada = novaDivInputs.querySelector('#estagiario-tarde-entrada')
    estagiarioTardeEntrada.name = `estagiario-tarde-entrada[${contador}]`
    const servidorSaida = novaDivInputs.querySelector('#estagiario-tarde-saida')
    servidorSaida.name = `estagiario-tarde-saida[${contador}]`
    //================================================

    //Configurações de botão para remoção do elemento

    const bntRemover = document.createElement('button');
    bntRemover.classList.add('btn-calendario', 'remover-div');
    bntRemover.type = "button"
    bntRemover.setAttribute('onclick', 'remover_input(this)')
    bntRemover.name = `btn-remove-${contador}`

    const icon = document.createElement('i');
    icon.classList.add('fa-solid', 'fa-x')

    bntRemover.appendChild(icon);

    //Container para botão
    const btnContainer = document.createElement('div')
    btnContainer.classList.add('items-horarios')
    btnContainer.style.display = "flex"
    btnContainer.style.alignItems = "flex-end"
    btnContainer.appendChild(bntRemover)


    //configura inpunts e botão de remoção

    const containerUpload = document.querySelector('.container-upload');
    const botoesEnvio = containerUpload.querySelector('.items-botoes');

    containerUpload.insertBefore(novaDivInputs, botoesEnvio);
    NovoContainerEstagiario.after(btnContainer)
}

//Remoção de botão
function remover_input(btnRemover) {
    const elementoName = btnRemover.name
    //Coleta somente valor dos inputs
    const index = elementoName.match(/btn-remove-(\d+)/);
    console.log(index[1])

    document.querySelector(`#container-inputs-${index[1]}`).remove();
}


