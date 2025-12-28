document.addEventListener('DOMContentLoaded', () => {

    // === MENU ACTIVE ======================================================
    const itensMenu = document.querySelectorAll('.nav-link');
    const caminhoAtual = window.location.pathname;

    itensMenu.forEach(li => {
        const link = li.querySelector('a');
        if (!link) return;

        const href = link.getAttribute('href');

        if (!href || href === '#') return;

        // normaliza o href
        const hrefNormalizado = href.replace('./', '');

        // verifica se a URL atual termina com o href
        if (caminhoAtual.endsWith(hrefNormalizado)) {
            li.classList.add('active');
        }
    });

    // === SIDEBAR ==========================================================
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');

    if (sidebar && sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    }

    // === MÁSCARA DE CPF ===================================================
    const cpfInput = document.getElementById('cpf');

    if (cpfInput) {
        cpfInput.addEventListener('input', function () {
            let cpf = this.value.replace(/\D/g, '');

            if (cpf.length > 3) cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
            if (cpf.length > 7) cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
            if (cpf.length > 11) cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

            this.value = cpf;
        });
    }

});
