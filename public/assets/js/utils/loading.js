//Funções de animação de carregamento

export function createLoading() {
    const loading = document.createElement('div');

    loading.classList.add('loading');

    loading.innerHTML = `
        <span></span>
        <span></span>
        <span></span>
        <p class='loading-text'>Carregando</p>
    `;

    return loading;
}

export function showLoading(containerLoading = document.querySelector('.loading-overlay')) {
    //Libera container de overlay
    
    containerLoading.classList.remove('hidden');

    let loading = containerLoading.querySelector('.loading');

    if (!loading) {
        loading = createLoading();
        containerLoading.appendChild(loading);
    }

    loading.style.display = 'block';
}

export async function hideLoading(containerLoading = document.querySelector('.loading-overlay')) {
    //Esconde container de overlay
    containerLoading.classList.add('esconder-overlay')
    setTimeout(() => {
        containerLoading.classList.add('hidden');
        const loading = containerLoading.querySelector('.loading');

        if (loading) {
            loading.style.display = 'none';
        }
    }, 800);

}