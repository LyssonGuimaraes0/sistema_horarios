document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');

    // Alternar a barra lateral
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });

});