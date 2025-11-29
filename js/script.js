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

//Ativação de modal Caso a senha ou usuario esteja errada!

function modal_error_senha(tentativa) {
    const BackgroundModalsenha = document.getElementById('modal-background-senha');
    const btnModalsenha = document.getElementById('btn-modal-senha');
    

    if(tentativa === true){
        BackgroundModalsenha.style.display = "block"
    }

    btnModalsenha.addEventListener('click', () =>{
        BackgroundModalsenha.style.display = "none"
    });
    
}


    //Confirmar caso a senha do usuario estiver certa

function validarSenhas() {
    var senha = document.getElementById('senha').value;
    var senhaConfirmar = document.getElementById('senha-confirmar').value;

    if (senha !== senhaConfirmar) {
        var tentativa = true;
        modal_error_senha(tentativa);
    }

    return false;
}



