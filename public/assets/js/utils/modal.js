//Função para apresentar Modal
export function apresentarModal(modal, condicao = null, mensagem = null) {
    const templateModal = document.querySelector('#modal-default');

    const clone = templateModal.content.cloneNode(true);

    const ModalBackground =
        clone.querySelector('.modal-background');

    if (modal === "modal-default") {
        confModal(ModalBackground, condicao, mensagem);
    }

    document.body.appendChild(clone);
    console.log(document.querySelector('.modal-background'));
}

//Função Para monta Modal
function confModal(ModalBackground,condicao, mensagem) {
    
    //Coleta Informações do formulario
    const ModalContainer = ModalBackground.querySelector('.modal-container');
    const ModalCabecalho = ModalContainer.querySelector('.modal-cabecalho');
    const ModalDescricao = ModalContainer.querySelector('.modal-descricao');
    const ModalBtn = ModalContainer.querySelector('.btn-modal');
    //botao cancelar
    const ModalBtnCancelar = ModalContainer.querySelector('#botao-cancelar');
    //botao okay ou confirmar
    const ModalBtncConfirmar = ModalContainer.querySelector('#botao-confirmar');

    //Limpa o modal
    ModalCabecalho.innerHTML = "";
    ModalDescricao.innerHTML = "";

    //Cria Variaveis de Modal
    var TituloModal = document.createElement('span');
    var DescricaoModal = document.createElement('p');
    DescricaoModal.innerHTML = mensagem;

    //Verifica qual tipo do modal
    switch (condicao) {
        case 'sucesso':
            var cor = "#14dd57"
            TituloModal.innerHTML = "<i class='fa-solid fa-circle-check'></i> Sucesso!";
            break;

        case 'falha':
            var cor = "#f03210ff"
            TituloModal.innerHTML = "<i class='fa-solid fa-circle-xmark'></i> Falha!";
            break;

        case 'alerta':

            var cor = "#f07c10ff"
            TituloModal.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Alerta!";
            break;
    }

    //Monta modal
    ModalContainer.style.border = `2px solid ${cor}`
    TituloModal.style.color = cor;
    ModalCabecalho.appendChild(TituloModal);
    ModalDescricao.appendChild(DescricaoModal)

    ModalBtn.onclick = () => {
        fecharModal();
    };


}

//Fecha modal
export function fecharModal() {
    document.querySelectorAll('.modal-background').forEach(modal => {
        modal.style.display = 'none';
    });
}