document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');

    // Alternar a barra lateral
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });

});

//Função para apresenta tdos os modais!

function apresenta_modal(condicao, mensagem) {

    var TituloModal = document.createElement('span');
    var DescricaoModal = document.createElement('p');
    DescricaoModal.innerHTML = mensagem;

    switch (condicao) {
        case 'sucesso':

            var cor = "#14dd57"
            TituloModal.innerHTML = "<i class='fa-solid fa-circle-check'></i> Sucesso!";
            conf_modal(TituloModal, DescricaoModal, cor);
            break;

        case 'falha':

            var cor = "#f03210ff"
            TituloModal.innerHTML = "<i class='fa-solid fa-circle-xmark'></i> Falha!";
            conf_modal(TituloModal, DescricaoModal, cor);

            break;

        case 'alerta':

            var cor = "#f07c10ff"
            TituloModal.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Alerta!";
            conf_modal(TituloModal, DescricaoModal, cor);

            break;

        default:
            break;
    }

    function conf_modal(titulo, descricao, cor) {
        //Coleta Informações do formulario
        const ModalBackground = document.querySelector('.modal-background');
        const ModalContainer = document.querySelector('.modal-container');
        const ModalCabecalho = document.querySelector('.modal-cabecalho');
        const ModalDescricao = document.querySelector('.modal-descricao');
        const ModalBtn = document.querySelector('.btn-modal');

        //Limpa o modal
        ModalCabecalho.innerHTML = "";
        ModalDescricao.innerHTML = "";

        //Monta modal
        ModalContainer.style.border = `2px solid ${cor}`
        titulo.style.color = cor;
        ModalCabecalho.appendChild(titulo);
        ModalDescricao.appendChild(descricao)

        //Apresnta o modal
        ModalBackground.style.display = "flex";

        ModalBtn.onclick = () => {
            ModalBackground.style.display = "none";
        };



    }
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









