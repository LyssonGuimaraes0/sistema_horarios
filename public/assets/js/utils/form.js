// Função padrão: recebe o formulário e retorna um objeto pronto com os dados
export function getFormData(form) {
    const formData = new FormData(form);
    
    // Object.fromEntries transforma as linhas do FormData em um objeto { nome: "João", email: "..." }
    return Object.fromEntries(formData.entries());
}