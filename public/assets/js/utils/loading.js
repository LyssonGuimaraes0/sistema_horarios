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

export function showLoading() {
    //Libera container de overlay
    let containerLoading = document.querySelector('.loading-overlay')
    containerLoading.classList.remove('hidden');

    let loading = containerLoading.querySelector('.loading');

    if (!loading) {
        loading = createLoading();
        containerLoading.appendChild(loading);
    }

    loading.style.display = 'block';
}

export function hideLoading() {
    //Esconde container de overlay
    let containerLoading = document.querySelector('.loading-overlay')
    containerLoading.classList.add('hidden');
    const loading = containerLoading.querySelector('.loading');

    if (loading) {
        loading.style.display = 'none';
    }
}