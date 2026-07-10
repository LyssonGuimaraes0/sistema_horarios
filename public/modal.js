import { showLoading, hideLoading } from "./loading.js";
import { formatDateBr } from "./assets/js/utils/format.js";

//Função modal Toast
export function showModalToast(mensagem, type = "sucess") {
    const template = document.querySelector('#modal-toast')
    const clone = template.content.cloneNode(true);

    const modalToast = clone.querySelector('.modal-toast');

    //Monta modal Toast
    const text = clone.querySelector('.container-text p');
    text.textContent = mensagem;

    modalToast.classList.add(type);

    document.body.appendChild(clone);

    //Toca animação e elimina o toast
    setTimeout(() => {
        modalToast.classList.add('hide');
        setTimeout(() => {
            modalToast.remove();
        }, 300)
    }, 1500);

}

//Montar modal de usuario

export function showModalUser(dataUser) {

    const templateModal = document.querySelector('#modal-info-user')
    const clone = templateModal.content.cloneNode(true);
    const modal = clone.querySelector('.modal-background');
    const loading = clone.querySelector('.loading-overlay')

    showLoading(loading);

    //Coleta todos inputs

    const AllInputs = clone.querySelectorAll(".input-modal-user")

    AllInputs.forEach(input => {
        const name = input.name;
        if (name in dataUser) {
            input.value = dataUser[name]
        }
    })

    //Alteração de elementos do modal
    let name = clone.querySelector('[name="nome"]');

    name.value = dataUser.nome;

    document.body.appendChild(clone)

    hideLoading(loading);

    return modal;

}


//Função modal Anexa frequencia
export async function showModalTimeSheet() {
    const template = document.querySelector('#modal-anexo')
    const clone = template.content.cloneNode(true);

    const modalTimeSheet = clone.querySelector('.modal-background');

    //Botão de input de arquivo
    const inputArquivo = modalTimeSheet.querySelector('.btn-upload');

    //Manipulação de botões
    const btnConfirmar = modalTimeSheet.querySelector('.btn-confirmar');
    const BtnCancelar = modalTimeSheet.querySelector('.btn-cancelar');

    document.body.appendChild(clone);

    BtnCancelar.addEventListener('click', () => {
        fecharModal();
        modalTimeSheet.remove();
    })

    return new Promise((resolve) => {
        btnConfirmar.addEventListener('click', () => {
            const arquivo = inputArquivo.files[0];

            //Verifica arquivo 
            if (!arquivo) {
                showModalToast("Selecione um arquivo", "error")
                return;
            }

            fecharModal();
            modalTimeSheet.remove();

            resolve({
                file: arquivo,
            }
            );
        })
    })

}


//Função para apresentar Modal certificado
export async function showModalCertificate(data = null) {
    const template = document.querySelector('#modalCertificate');
    const clone = template.content.cloneNode(true);

    const modalCertificate = clone.querySelector('.modal-background');

    if (data === null) {
        return modalCertificate
    }

    //Manipulando datas
    const dateAtual = new Date(data + 'T00:00');


    //Aplicação de valores nos elementos 

    const dataOrigem = modalCertificate.querySelector('#data-origem');
    const dataInicio = modalCertificate.querySelector('#data-inicio');
    const dataFim = modalCertificate.querySelector('#data-fim');

    dataOrigem.value = formatDateBr(dateAtual)
    dataInicio.value = formatDateBr(dateAtual)

    //Por padrão define 1
    let dataFimValue = new Date(dateAtual);
    dataFimValue.setDate(dataFimValue.getDate());

    dataFim.value = formatDateBr(dataFimValue)
    let dataFormatada = dataFimValue.toISOString().split('T')[0];

    modalCertificate.querySelector('#input-dias-atestados').addEventListener('change', function () {

        const dias = Math.round(
            Number(modalCertificate.querySelector('#input-dias-atestados').value)
        );


        dataFimValue = new Date(dateAtual)

        //Altera baseado na quantidade de dias adicionado
        dataFimValue.setDate(dataFimValue.getDate() + dias - 1);

        dataFormatada = dataFimValue.toISOString().split('T')[0];

        dataFim.value = formatDateBr(dataFimValue)
    })

    const dropdown = modalCertificate.querySelector('.dropdown-justificativa')

    const inputArquivo = modalCertificate.querySelector('.btn-upload');

    //Adicionar horario padrão aos os imputs

    document.body.appendChild(clone);

    //Manipulação de botões
    const btnConfirmar = modalCertificate.querySelector('.btn-confirmar');
    const BtnCancelar = modalCertificate.querySelector('.btn-cancelar');

    BtnCancelar.addEventListener('click', () => {
        fecharModal();
        modalCertificate.remove();
    })


    //Configuração para devolver dados
    return new Promise((resolve) => {
        btnConfirmar.addEventListener('click', () => {

            const arquivo = inputArquivo.files[0];

            //Verifica select
            if (dropdown.value == "") {
                showModalToast("Selecione o motivo do atestado", "error")
                return;
            }

            //Verifica arquivo 
            if (!arquivo) {
                showModalToast("Selecione um arquivo", "error")
                return;
            }



            //Fecha modal e devolve dados
            fecharModal();
            modalCertificate.remove();

            resolve({
                dateStart: data,
                dateEnd: dataFormatada,
                [dropdown.name]: dropdown.value,
                file: arquivo
            })
        })
    })
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