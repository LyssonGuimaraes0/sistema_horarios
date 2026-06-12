//Gera cards de lista de horarios

export function createCardList(template, dadosData) {
    const cloneCard = template.content.cloneNode(true);
    const dataHeader = cloneCard.querySelector('#date-circule');
    const weekHeader = cloneCard.querySelector('#header-week-name');

    //Container de Inputs e botões
    const containerInputs = cloneCard.querySelectorAll('.items-horarios');
    const containerbtn = cloneCard.querySelector('.items-botoes');

    //Coleta de data
    const dia = dadosData.date.split('-')[2];

    //Adiciona data e dia da semana
    dataHeader.textContent = dia;
    weekHeader.textContent = dadosData.weekName;

    console.log(dadosData.holiday)
    
    //Verifica caso seja final de semana ou Feriado
    if (dadosData.weekend === true || dadosData.holiday != false) {
        //Remove container de Inputs e botões de envio
        containerInputs.forEach(containerInput => {
            containerInput.remove();
        });

        containerbtn.remove();
    }

    return cloneCard;

}