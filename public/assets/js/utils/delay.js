//Função de Delay padrão para funções simples
export function delay(ms) {
    return new Promise(resolve => {
        setTimeout(resolve, ms);
    });
}

//=================//

// Função Debounce usando o conceito de Promise cancelável
export function debouncePromise(ms) {
    let timeoutId;
    
    return () => {
        // Toda vez que for chamada, cancela a promessa anterior
        clearTimeout(timeoutId);
        
        return new Promise(resolve => {
            timeoutId = setTimeout(resolve, ms);
        });
    };
}