document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');

    // Alternar a barra lateral
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });

});

//Função para apresenta tdos os modais!

function apresenta_modal(idmodal, idbtn, tentativa = true) {

    // Só abre se tentativa for true
    if (tentativa !== true) {
        return;
    }

    const modal = document.getElementById(idmodal);
    const btnModal = document.getElementById(idbtn);

    modal.style.display = "block";

    // Evita múltiplos addEventListeners
    btnModal.onclick = () => {
        modal.style.display = "none";
    };
}

//Confirmar caso a senha do usuario estiver certa(Utilizar modal acima)

function validarSenhas() {
    var senha = document.getElementById('senha').value;
    var senhaConfirmar = document.getElementById('senha-confirmar').value;

    if (senha !== senhaConfirmar) {
        apresenta_modal('modal-senha','btn-senha');
        return false
    }
    return true;
}







