//Funções de animação de carregamento

export function createLoading() {
    const loading = document.createElement('div');

    loading.classList.add('loading');

    loading.innerHTML = `
        <span></span>
        <span></span>
        <span></span>
    `;

    return loading;
}

export function showLoading(container) {
    let loading = container.querySelector('.loading');

    if (!loading) {
        loading = createLoading();
        container.appendChild(loading);
    }

    loading.style.display = 'block';
}

export function hideLoading(container) {
    const loading = container.querySelector('.loading');

    if (loading) {
        loading.style.display = 'none';
    }
}