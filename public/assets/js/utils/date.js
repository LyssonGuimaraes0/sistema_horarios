//Função para calcular periodo entre datas
export function gerarPeriodo(dataInicio, dataFim) {
    const datas = [];
    
    const atual = new Date(dataInicio + 'T00:00');
    const fim   = new Date(dataFim   + 'T00:00');
    
    while (atual <= fim) {
        datas.push(atual.toISOString().split('T')[0]);
        atual.setDate(atual.getDate() + 1);
    }
    
    return datas;
}