//Função modal Toast
export function showModalToast(mensagem, type = "sucess") {
    const template = document.querySelector('#modal-toast')
    const clone = template.content.cloneNode(true);

    const modalToast = clone.querySelector('.modal-toast');

    //Monta modal Toast
    const text = clone.querySelector('.container-text p');
    text.textContent = mensagem;

    console.log(mensagem)
    console.log(type)

    modalToast.classList.add(type);

    document.body.appendChild(clone);

    //Toca animação e elimina o toast
    setTimeout(() => {
       modalToast.classList.add('hide');
       setTimeout(()=>{
            modalToast.remove();
       }, 300) 
    }, 1500);

}



//Função para apresentar Modal
export async function apresentarModal(modal, condicao = null, mensagem = null) {
    const templateModal = document.querySelector('#modal-default');
    const clone = templateModal.content.cloneNode(true);

    const ModalBackground = clone.querySelector('.modal-background');

    document.body.appendChild(clone);

    if (modal === "modal-default") {
        return await confModal(ModalBackground, condicao, mensagem);
    }
}
//Função Para monta Modal
function confModal(ModalBackground, condicao, mensagem) {
    return new Promise((resolve) => {

        const ModalContainer = ModalBackground.querySelector('.modal-container');
        const ModalCabecalho = ModalContainer.querySelector('.modal-cabecalho');
        const ModalDescricao = ModalContainer.querySelector('.modal-descricao');
        const ModalBtn = ModalContainer.querySelector('.btn-modal');
        const ModalBtnConfirmar = ModalContainer.querySelector('#botao-confirmar');

        ModalCabecalho.innerHTML = "";
        ModalDescricao.innerHTML = "";

        let cor = "";

        const TituloModal = document.createElement('span');
        const DescricaoModal = document.createElement('p');
        DescricaoModal.innerHTML = mensagem;

        if (condicao === 'sucesso') {
            cor = "#14dd57";
            TituloModal.innerHTML = "<i class='fa-solid fa-circle-check'></i> Sucesso!";
        }

        if (condicao === 'falha') {
            cor = "#f03210ff";
            TituloModal.innerHTML = "<i class='fa-solid fa-circle-xmark'></i> Falha!";
        }

        if (condicao === 'alerta') {
            cor = "#f07c10ff";
            TituloModal.innerHTML = "<i class='fa-solid fa-triangle-exclamation'></i> Alerta!";

            const BtnCancelar = document.createElement('button');
            BtnCancelar.classList.add('btn-login');
            BtnCancelar.style.border = "2px solid #f03210ff";
            BtnCancelar.textContent = "Cancelar";

            BtnCancelar.onclick = () => {
                fecharModal();
                resolve(false);
            };

            ModalBtn.appendChild(BtnCancelar);
        }

        ModalContainer.style.border = `2px solid ${cor}`;
        TituloModal.style.color = cor;

        ModalCabecalho.appendChild(TituloModal);
        ModalDescricao.appendChild(DescricaoModal);

        // botão confirmar original do template
        if (ModalBtnConfirmar) {
            ModalBtnConfirmar.onclick = () => {
                fecharModal();
                resolve(true);
            };
        }
    });
}


//Fecha modal
export function fecharModal() {
    document.querySelectorAll('.modal-background').forEach(modal => {
        modal.style.display = 'none';
    });
}