// == Ponto Facultativo ====================================================

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
    servidorPublicoSaidaAlmoco.name = `servidor-publico-saida_almoco[${contador}]`
    const servidorPublicoVoltaAlmoco = novaDivInputs.querySelector('#servidor-publico-volta-almoco')
    servidorPublicoVoltaAlmoco.name = `servidor-publico-volta_almoco[${contador}]`
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


    //Define a posição onde o elemento vai ficar
    const itens = novaDivInputs.querySelectorAll('.items-horarios')
    const ultimo = itens[itens.length - 1]

    //configura inpunts e botão de remoção

    const containerUpload = document.querySelector('.container-upload');
    const botoesEnvio = containerUpload.querySelector('.items-botoes');

    containerUpload.insertBefore(novaDivInputs, botoesEnvio);
    ultimo.after(btnContainer)

}

//Remoção de botão
function remover_input(btnRemover) {
    const elementoName = btnRemover.name
    //Coleta somente valor dos inputs
    const index = elementoName.match(/btn-remove-(\d+)/);
    console.log(index[1])

    document.querySelector(`#container-inputs-${index[1]}`).remove();
}

//Libera container base
const selecaoTipoFuncionario = document.querySelector('#selecao-tipo-funcionario')

const containerServidorPublico = document.querySelector('.container-servidor-publico')
const containerEstagiario = document.querySelector('.container-estagiario')

function LiberaContainer(dropdown, elementoServidor, elementoEstagiario) {
    elementoServidor.style.display = "none";
    elementoEstagiario.style.display = "none";
    dropdown.addEventListener('change', function () {
        let valor = dropdown.value;

        switch (valor) {
            case "Servidor Publico":
                elementoServidor.style.display = "block";
                elementoEstagiario.style.display = "none";

                break;
            case "Estagiario":
                elementoServidor.style.display = "none";
                elementoEstagiario.style.display = "block";
                break;
            case "Ambos":
                elementoServidor.style.display = "block";
                elementoEstagiario.style.display = "block";
                break;


        }
    });
}

LiberaContainer(selecaoTipoFuncionario, containerServidorPublico, containerEstagiario);



//Adicionar esculta para verificar caso o botão foi apertado para cria o elemento.

const adicionarDiv = document.querySelector('.adicionar-div')

adicionarDiv.addEventListener('click', function () {
    adicionar_input();
});