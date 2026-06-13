//Função de criação de Options 
export function createOptions(select, dados) {
    dados.forEach(dado => {
        const option = document.createElement('option');

        //Verifica se é um objeto ou array
        if (typeof dado === 'object' && dado !== null) {
            option.value = dado.id;
            option.textContent = dado.name;
        } else {
            option.value = dado;
            option.textContent = dado;
        }

        select.appendChild(option);
    });
}