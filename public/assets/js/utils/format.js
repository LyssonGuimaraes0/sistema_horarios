//Formata texto para so permitir numeros

export function formatTextToNumber(valor) {
    return valor = valor.replace(/\D/g, '');
}

//Formata texto para so permitir string sem caracteres
export function formatTextToStringWithNumber(valor) {
    return valor = valor.replace(/[^a-zA-ZÀ-ÿ0-9 .,´`@]/g, '');
}

//Formata texto para so permitir string sem caracteres e numero
export function formatTextToString(valor) {
    return valor = valor.replace(/[^a-zA-ZÀ-ÿ .,´`@]/g, '');
}

//Formata texto para so permitir string sem caracteres
export function formatTextToCPF(valor) {
    valor = formatTextToNumber(valor);

    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
    valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

    return valor;
}

//Formata para data estilo brasileiro
export function formatDateBr(data) {
    return new Date(data).toLocaleDateString('pt-BR');
}
