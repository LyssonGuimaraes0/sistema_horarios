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

//Função de criação de botões de edição

export function createButtonsCalendar(container) {
    container.innerHTML = `
        <i class="fa-solid fa-pen-to-square botao-calendario"
          data-action="edit">
        </i>

        <i class="fa-solid fa-trash-can botao-calendario"
        data-action="delete"></i>
    `;

    /*             <i class="fa-solid fa-check btn-confirmar botao-calendario d-none"
               id="btn-confirmar">
            </i>
    
            <i class="fa-solid fa-xmark btn-cancelar botao-calendario d-none"
               id="btn-cancelar">
            </i> */

    return container;
}