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
                    setReadonly(input)

                    //Limpa botões autal
                    containerbtn.replaceChildren();

                    //Verifica se é a o card atual tem atestado
                    if (dadosData.certificate != null) {
                        let dataInicio = dadosData.certificate.data_inicio;

                        //Verifica se o dia bate com dia atual
                        if (dataInicio == dadosData.date) {
                            //Caso seja o primeiro dia somente coloca o botão de deletar
                            //Limpa container
                            containerbtn.innerHTML = ""

                            containerbtn.innerHTML = `
                            <span>Atestado</span>

                            <i class="fa-solid fa-trash-can botao-calendario"data-action="delete"></i>
                            `;
                        } else {
                            certificateButtonCalendar(containerbtn)
                        }

                    } else {
                        //Cria botões
                        createButtonsCalendar(containerbtn);
                    }

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

//Função de alteração de botão de edição para confirmar e cancelar

export function deleteButtonCalendar(container) {
    //Limpa container
    container.innerHTML = ""

    container.innerHTML = `
        <i class="fa-solid fa-trash-can botao-calendario"
                data-action="delete">
        </i>
    `;
    return container;
}

export function certificateButtonCalendar(container) {
    //Limpa container
    container.innerHTML = ""

    container.innerHTML = `
        <span>Atestado</span>
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

//Função de alteração de botão de anexar frequencia

export function alterarBtnAnexarFrequencia(btn, status = "analise") {
    //Limpa container
    btn.disabled = true
    btn.innerHTML = ""
    btn.classList.remove('valid', 'analysis');

    switch (status) {

        case "analise":
            btn.classList.add('analysis');
            btn.innerHTML = "<i class='fa-solid fa-file-circle-question'></i><p>Folha em Ánalise</p>"

            break;

        case "validado":
            btn.classList.add('valid');
            btn.innerHTML = "<i class='fa-solid fa-file-circle-check'></i></i><p>Folha Anexada!</p>"

            break;

        default:
            btn.innerHTML = "<i class='fa-solid fa-upload card-icon'></i><p>Anexar Frequência</p>"
            break;

    }

    return btn;
}



// =================================================================


//Setar Readonly

export function setReadonly(input) {
    return input.setAttribute("readonly", "true");
}

//Remove Readonly

export function RemoveReadonly(input) {
    return input.removeAttribute("readonly", "true");
}