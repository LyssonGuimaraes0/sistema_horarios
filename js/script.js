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


//Máscara de CPF

document.getElementById('cpf').addEventListener('input', function () {
    let cpf = this.value;

    // 1) remove tudo que NÃO é número
    cpf = cpf.replace(/\D/g, '');

    // 2) coloca o primeiro ponto depois de 3 números
    if (cpf.length > 3) {
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
    }

    // 3) coloca o segundo ponto depois de 6 números
    if (cpf.length > 7) {
        cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
    }

    // 4) coloca o traço nos últimos dois dígitos
    if (cpf.length > 11) {
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    }

    this.value = cpf;
});









