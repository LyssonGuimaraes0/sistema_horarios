//Gera cards de lista de horarios

export function createCardList(template, dadosData) {
    const cloneCard = template.content.cloneNode(true);

    const card = cloneCard.querySelector('.container-horarios');
    card.dataset.date = dadosData.date;

    const dataHeader = cloneCard.querySelector('#date-circule');
    const weekHeader = cloneCard.querySelector('#header-week-name');
    const monthHeader = cloneCard.querySelector('#header-month');

    //Container de Inputs e botões
    const containerInputs = cloneCard.querySelectorAll('.items-horarios');
    const containerbtn = cloneCard.querySelector('.items-botoes');

    //Coleta de data
    const dia = dadosData.date.split('-')[2];

    //Adiciona data e dia da semana
    dataHeader.textContent = dia;
    weekHeader.textContent = dadosData.weekName;
    monthHeader.textContent = `${dadosData.month}\\${dadosData.year}`

    //Verifica caso seja final de semana ou Feriado

    let mensagem
    let containerAtual = null

    if (dadosData.weekend === true || dadosData.holiday != false) {
        //Remove container de Inputs e botões de envio
        containerInputs.forEach((containerInput, index) => {
            if (index > 0) {
                containerInput.remove();
            } else {

                containerInput.replaceChildren();
                containerAtual = containerInput;
                mensagem = (dadosData.holiday != false) ?
                    dadosData.holiday : "Fim de Semana";
            }

        });

        if (containerAtual) {
            const textoSpan = document.createElement('span');
            textoSpan.textContent = mensagem;
            //Apresenta container
            containerAtual.appendChild(textoSpan);
        }

        containerbtn.remove();
    }

    //Verifica se existe alguma data registrada 
    const attendance = dadosData.attendance
    if (attendance) {
        //Coleta os inputs e armazena baseado no name dele
        Object.entries(attendance).forEach(([chave, valor]) => {

            containerInputs.forEach(container => {
                const input = container.querySelector(
                    `input[name="${chave}"]`
                );
                //Se possuir elementos altera os botões para botões de edição
                if (input) {
                    input.value = valor;
                    input.value = new Date(`1970-01-01 ${valor}`).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                    input.setAttribute("readonly", "true");

                    //Limpa botões autal
                    containerbtn.replaceChildren();
                    //Cria botões
                    createButtonsCalendar(containerbtn);

                }
            });
        });
    }


    return cloneCard;

}







export function buttonSubmitCalendar(container) {
    //Limpa container
    container.innerHTML = ""

    container.innerHTML = `
        <button type='submit' class='btn-calendario' name='dia' data-action="submit" >Confirmar</button>

        <i class='fa-solid fa-file-alt botao-calendario' data-action="certificate"></i>
    `;

    return container;
}

//Função de criação de botões de edição e delete
export function createButtonsCalendar(container) {
    //Limpa container
    container.innerHTML = ""

    container.innerHTML = `
        <i class="fa-solid fa-pen-to-square botao-calendario"
          data-action="edit">
        </i>

        <i class="fa-solid fa-trash-can botao-calendario"
        data-action="delete"></i>
    `;


    return container;
}

//Função de alteração de botão de edição para confirmar e cancelar

export function alterButtonsCalendar(container) {
    //Limpa container
    container.innerHTML = ""

    container.innerHTML = `
        <i class="fa-solid fa-check btn-confirmar botao-calendario"
               data-action="confirm">
        </i>
    
        <i class="fa-solid fa-xmark btn-cancelar botao-calendario"
               data-action="cancel">
        </i> 

        <i class="fa-solid fa-trash-can botao-calendario"
                data-action="delete">
        </i>
    `;

    return container;
}