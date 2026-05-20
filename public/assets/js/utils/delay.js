//Função de Delay padrão para funções simples
export function delay(ms) {
    return new Promise(resolve => {
        setTimeout(resolve, ms);
    });
}

//=================//