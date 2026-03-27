
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

AccordionBnts.forEach(accordionBtn => {

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

let containerEmEdicao = null;

function editar_horario(elemento) {
    const container = elemento.closest('.container-horarios');
    const inputs = container.querySelectorAll('.horario-input');
    const btnConfirmar = container.querySelector('.btn-confirmar');
    const btnCancelar = container.querySelector('#btn-cancelar');

    // 🚫 só um por vez
    if (containerEmEdicao && containerEmEdicao !== container) {
        alert('Finalize ou cancele a edição atual antes.');
        return;
    }

    containerEmEdicao = container;

    inputs.forEach(input => {
        input.dataset.valorAntigo = input.value;
        input.removeAttribute('readonly');
    });

    elemento.classList.add('d-none');
    btnConfirmar?.classList.remove('d-none');
    btnCancelar?.classList.remove('d-none');
}


function confirmar_horario(elemento) {
    const container = elemento.closest('.container-horarios');
    const inputs = container.querySelectorAll('.horario-input');
    const btnEditar = container.querySelector('#btn-editar');
    const btnCancelar = container.querySelector('#btn-cancelar');

    const dados = {
        data: container.dataset.data,
        dias: {}
    };


    inputs.forEach(input => {
        const match = input.name.match(/^([a-z_]+)\[(\d+)\]$/);
        if (!match) return;

        const campo = match[1];
        const dia = match[2];

        if (!dados.dias[dia]) {
            dados.dias[dia] = {};
        }

        dados.dias[dia][campo] = input.value ?? '';
        input.setAttribute('readonly', 'readonly');
    });

    elemento.classList.add('d-none');
    btnCancelar?.classList.add('d-none');
    btnEditar?.classList.remove('d-none');

    containerEmEdicao = null;

    salvarHorario(dados);
}

function cancelar_horario(elemento) {
    const container = elemento.closest('.container-horarios');
    const inputs = container.querySelectorAll('.horario-input');
    const btnEditar = container.querySelector('#btn-editar');
    const btnConfirmar = container.querySelector('#btn-confirmar');
    const btnCancelar = container.querySelector('#btn-cancelar');

    inputs.forEach(input => {
        input.value = input.dataset.valorAntigo ?? '';
        input.setAttribute('readonly', 'readonly');
    });

    elemento.classList.add('d-none');
    btnConfirmar?.classList.add('d-none');
    btnEditar?.classList.remove('d-none');

    containerEmEdicao = null;
}


function salvarHorario(dados) {
    fetch('./settings/editar_horario.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dados)
    })
        .then(r => r.json())
        .then(resp => {
            if (resp.status === 'ok') {
                window.location.href = resp.redirect;
            }
        });
}

// == Adicionar Justificativa ====================================================
function adicionar_justificativa(data) {

    ModalBackground = document.getElementById('modal-justificativa');
    var dataModificar = document.querySelector('#data_justificativa');
    var inputDiasAtestados = document.querySelector('#input-dias-atestados');
    const inputOrigem = document.querySelector('#data-origem');
    const inputInicio = document.querySelector('#data-inicio');
    const inputFim = document.querySelector('#data-fim');
    //Reparte data enviada
    const [ano, mes, dia] = data.split('-').map(Number);
    const dataOficial = new Date(ano, mes - 1, dia);

    //formatação de data

    const diaorigem = String(dataOficial.getDate()).padStart(2, '0');       // adiciona zero se precisar
    const mesorigem = String(dataOficial.getMonth() + 1).padStart(2, '0');  // +1 porque meses começam em 0
    const anoorigem = dataOficial.getFullYear();

    const dataorigem = `${diaorigem}/${mesorigem}/${anoorigem}`;

    //Adicionar valor no input
    inputOrigem.value = dataorigem;
    inputInicio.value = dataorigem;
    inputFim.value = dataorigem;



    inputDiasAtestados.addEventListener('input', () => {
        const dataNova = new Date(dataOficial);
        const dias = Number(inputDiasAtestados.value) || 0;
        dataNova.setDate(dataNova.getDate() + dias - 1);

        const diafim = String(dataNova.getDate()).padStart(2, '0');       // adiciona zero se precisar
        const mesfim = String(dataNova.getMonth() + 1).padStart(2, '0');  // +1 porque meses começam em 0
        const anofim = dataNova.getFullYear();

        const datafim = `${diafim}/${mesfim}/${anofim}`;


        inputFim.value = datafim;
    });










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