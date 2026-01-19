document.addEventListener('DOMContentLoaded', () => {




    // === SIDEBAR ===========================================================
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    }


    // === MÁSCARA DE CPF ====================================================
    const cpfInput = document.getElementById('cpf');

    if (cpfInput) {
        cpfInput.addEventListener('input', function () {

            let cpf = this.value;

            // remove tudo que não é número
            cpf = cpf.replace(/\D/g, '');

            if (cpf.length > 3) {
                cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
            }

            if (cpf.length > 7) {
                cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
            }

            if (cpf.length > 11) {
                cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            }

            this.value = cpf;
        });
    }

    // === MÁSCARA DE HORARIOS ====================================================
    const inputHorarios = document.querySelectorAll('.horario-input');

    inputHorarios.forEach((input) => {
        input.addEventListener('input', function () {
            // 1. Limpeza inicial e máscara básica
            this.style.border = "none";
            let horario = this.value.replace(/\D/g, '');
            horario = horario.slice(0, 4);

            // Validações de formato (HH:MM)
            if (horario.length === 1 && !/[0-2]/.test(horario[0])) { this.value = ''; return; }
            if (horario.length === 3 && !/[0-5]/.test(horario[2])) { this.value = horario.slice(0, 2); return; }

            // 2. LÓGICA DE COMPARAÇÃO TOTAL
            if (horario.length === 4) {
                const containerDia = this.closest('.container-horarios');
                const inputsDoDia = Array.from(containerDia.querySelectorAll('.horario-input'));
                const indiceAtual = inputsDoDia.indexOf(this);

                const minAtual = Number(horario.slice(0, 2)) * 60 + Number(horario.slice(2));

                // VERIFICA TODOS OS CAMPOS ANTERIORES
                for (let i = 0; i < indiceAtual; i++) {
                    if (inputsDoDia[i].value.length === 5) {
                        let val = inputsDoDia[i].value.replace(':', '');
                        let minAnt = Number(val.slice(0, 2)) * 60 + Number(val.slice(2));
                        if (minAtual <= minAnt) {
                            this.style.border = "2px solid red";
                            this.value = '';
                            return;
                        }
                    }
                }

                // VERIFICA TODOS OS CAMPOS POSTERIORES (Aqui resolve o Índice 0 vs 1)
                for (let i = indiceAtual + 1; i < inputsDoDia.length; i++) {
                    if (inputsDoDia[i].value.length === 5) {
                        let val = inputsDoDia[i].value.replace(':', '');
                        let minProx = Number(val.slice(0, 2)) * 60 + Number(val.slice(2));
                        if (minAtual >= minProx) {
                            this.style.border = "2px solid red";
                            this.value = '';
                            return;
                        }
                    }
                }
            }

            // 3. Aplica a máscara visual final
            if (horario.length > 2) {
                horario = horario.replace(/^(\d{2})(\d{1,2})$/, '$1:$2');
            }
            this.value = horario;
        });
    });


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

});

// === ACCORDION ACTIVE ======================================================

const AccordionBnts = document.querySelectorAll('.accordion-header');

AccordionBnts.forEach(accordionBtn =>{

    accordionBtn.addEventListener('click', () => {
      const item = accordionBtn.parentElement;

      item.classList.toggle('active');
    });

});





//Fecha modal
function fechar_modal() {
    document.querySelectorAll('.modal-background').forEach(modal => {
        modal.style.display = 'none';
    });
}

// == EDITAR HORARIO ====================================================

function editar_horario(elemento) {
    const ContainerDia = elemento.closest('.container-horarios');
    const inputsDias = ContainerDia.querySelectorAll('.horario-input');
    //Coleta Valores
    const modoEdicao = inputsDias[0].hasAttribute('readonly');

    //Entra no modo edição
    if (modoEdicao) {
        inputsDias.forEach(inputdia => {
            //define valor antigo
            inputdia.dataset.valorAntigo = inputdia.value;
            elemento.classList.replace('fa-pen-to-square', 'fa-x');
            inputdia.removeAttribute('readonly');
        });

        //Bloquea novamente é retorna aos valores padrões  
    } else {
        inputsDias.forEach(inputdia => {
            if (inputdia.dataset.valorAntigo !== undefined) {
                inputdia.value = inputdia.dataset.valorAntigo;
            }
            elemento.classList.replace('fa-x', 'fa-pen-to-square');
            inputdia.setAttribute('readonly', 'readonly');
        });

    }

}

// == Adicionar Justificativa ====================================================
    function adicionar_justificativa(data){
        ModalBackground = document.getElementById('modal-justificativa');
        var dataModificar = document.querySelector('#data_justificativa');
        dataModificar.value = data;
        ModalBackground.style.display = "block";
        
    }


//Função de oculta calendario ate o usuario clica no btn

function abrircalendario() {

    const ContainerCalendario = document.querySelector('.calendario-container');

    ContainerCalendario.style.display = "block";
}



//Função para apresenta tdos os modais!

function apresenta_modal(condicao, mensagem,) {

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

    function conf_modal(titulo, descricao, cor, funcao = null) {
        //Coleta Informações do formulario
        const ModalBackground = document.querySelector('.modal-background');
        const ModalContainer = document.querySelector('.modal-container');
        const ModalCabecalho = document.querySelector('.modal-cabecalho');
        const ModalDescricao = document.querySelector('.modal-descricao');
        const ModalBtn = document.querySelector('.btn-modal');
        //botao cancelar
        const ModalBtnCancelar = document.querySelector('#botao-cancelar');
        //botao okay ou confirmar
        const ModalBtncConfirmar = document.querySelector('#botao-confirmar');

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
            fechar_modal();
        };



    }
}