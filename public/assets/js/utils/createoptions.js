export function createOptions(select, dados) {
    if (Array.isArray(dados)) {
        dados.forEach(dado => {
            const option = document.createElement("option");

            if (typeof dado === "object" && dado !== null) {
                option.value = dado.id;
                option.textContent = dado.name;
            } else {
                option.value = dado;
                option.textContent = dado;
            }

            select.appendChild(option);
        });
    } else {
        Object.entries(dados).forEach(([value, text]) => {
            const option = document.createElement("option");

            option.value = value;
            option.textContent = text;

            select.appendChild(option);
        });
    }
}