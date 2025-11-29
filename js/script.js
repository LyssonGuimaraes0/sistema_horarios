document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');

    // Alternar a barra lateral
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });

});

//Ativação de modal Caso a senha ou usuario esteja errada!

function modal_error(tentativa) {
    const BackgroundModal = document.getElementById('modal-background');
    const btnModal = document.getElementById('btn-modal');

    if(tentativa === true){
        BackgroundModal.style.display = "block"
    }

    btnModal.addEventListener('click', () =>{
        BackgroundModal.style.display = "none"
    });
    
}

