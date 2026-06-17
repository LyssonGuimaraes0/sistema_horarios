//Verifica inputs de horarios comparando valores 
export function verificarInputs(input, index, inputHorarios) {

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