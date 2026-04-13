



// == Ponto Facultativo ====================================================

// == Verificar
document.addEventListener('input', function (e) {

    if (!e.target.classList.contains('horario-input')) return;

    const input = e.target;

    // pega o grupo correto (manhã OU tarde)
    const grupo = input.closest('.item-horario');

    // pega só os inputs desse grupo
    const inputsDoGrupo = Array.from(
        grupo.querySelectorAll('.horario-input')
    );

    const indexCorreto = inputsDoGrupo.indexOf(input);

    Verificar_inputs(input, indexCorreto, inputsDoGrupo);

});


// == Validação para Data adicionados =====

const InputDataPontoFacultativo = document.querySelector('#data-feriado')
let ValorInputPontoFacultativo = null
const aviso = document.createElement("span")
aviso.textContent = "Selecione uma data Valida"
aviso.style.color = "red"
aviso.style.display = "none"

//Valida Primeira Data do Ponto Facultativo
InputDataPontoFacultativo.addEventListener('input', function () {
    ValorInputPontoFacultativo = InputDataPontoFacultativo.value
    let Data = ValorInputPontoFacultativo.split('-')

    let DataFormatada = `${Data[2]}/${Data[1]}/${Data[0]}`

    //Valida se ano é menor qua anoAtual
    if (Data[0] < AnoAtual || ListaFeriados[DataFormatada]) {
        validacao_campo(InputDataPontoFacultativo, true)
        aviso.style.display = "block"
        InputDataPontoFacultativo.parentNode.insertBefore(
            aviso,
            InputDataPontoFacultativo
        )
    }else {
        aviso.style.display = "none"
        validacao_campo(InputDataPontoFacultativo, false)
    }

})

//Valida Dados de Inicio e Fim

//Evento q coleta todos elementos recem criados
document.addEventListener('input', function (e) {

    if (!e.target.classList.contains('data-periodo')) return

    const input = e.target

    const inputs = document.querySelectorAll('.data-periodo')
    const index = Array.from(inputs).indexOf(input)

    if (input.value <= InputDataPontoFacultativo.value) {
        input.style.border = "2px solid red"
        input.value = ""
        return
    }

    Verificar_inputs(input, index, inputs)
})


// == Adicionar Input de ponto facultativo ====================================================

let contador = 0;


function adicionar_input(btn) {


    console.log("INICIO");

    const containerUpload = btn.closest('.container-upload');
    const botoesEnvio = containerUpload.querySelector('.items-botoes');

    console.log("containerUpload:", containerUpload);
    console.log("botoesEnvio:", botoesEnvio);

    contador++;

    const divInputs = document.querySelector('.container-input')
    console.log("2 - container:", divInputs);

    const novaDivInputs = divInputs.cloneNode(true);
    novaDivInputs.id = `container-inputs-${contador}`
    console.log("3 - clonou", novaDivInputs);

    //Bloqueia containers Servidor e Funcionario

    const NovoSelecaoTipoFuncionario = novaDivInputs.querySelector('#selecao-tipo-funcionario')
    console.log("4 - select:", NovoSelecaoTipoFuncionario);
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

    containerUpload.insertBefore(novaDivInputs, botoesEnvio);

    ///

    /* containerUpload.insertBefore(novaDivInputs, botoesEnvio); */
    /*     document.body.appendChild(novaDivInputs);
        NovoContainerEstagiario.after(btnContainer)
    
        console.log("ADICIONADO:", novaDivInputs);
        console.log("containerUpload:", containerUpload);
        console.log("botoesEnvio:", botoesEnvio);
        */
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
            case "Servidor Público":
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
    adicionar_input(adicionarDiv)
    console.log(document.querySelector('.adicionar-div'))
});




