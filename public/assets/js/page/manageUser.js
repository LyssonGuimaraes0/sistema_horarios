import { request } from "../service/ajax.js";
import {
    showModalUser,
    fecharModal,
    showModalToast,
    showModalCertificate
} from "../utils/modal.js";
import { showLoading, hideLoading } from "../utils/loading.js";
import { debouncePromise} from "../utils/delay.js";
import { createOptions } from "../utils/createoptions.js";
import { createCardUser } from "../utils/card.js";
import { setReadonly, RemoveReadonly } from "../utils/card.js";
import { formatDateBr } from "../utils/format.js";

//Carrega anos validos
let years;
let response;
let dataUser
const formData = new FormData();
try {

    response = await request(`${urlBase}/api/attendance/available-periods`)

    if (!response || response.success != true) {
        throw new Error(response?.error);
    }

    //Separa variaveis de ano e mes
    years = response.data.years;

} catch (error) {
    showModalToast(error, "erro")
}

const selectSetor = document.querySelector('#dropdown-setor')

//Coleta setores Registrados no sistema
let optionSetor

try {

    response = await request(`${urlBase}/api/user/sectors`)

    if (!response || response.success != true) {
        throw new Error(response?.message || "Erro interno no servidor");
    }
    optionSetor = response.data


} catch (error) {
    showModalToast(error, "erro")
}



//Gera options para selectSetor

const templateCard = document.querySelector('#card-manager-user')
const containerUsers = document.querySelector('#container-pessoas')
const tableUser = document.querySelector('.list-user-calendario')

//Cria Select de setores
createOptions(selectSetor, optionSetor)

//Selecionar setor selecionado
selectSetor.addEventListener('change', async (event) => {

    showLoading()

    tableUser.innerHTML = "";
    //Libera container users
    containerUsers.style.display = "flex";

    //Pega Valor da Seleção escolhida
    const valorSelecionado = event.target.value;

    try {

        response = await request(`${urlBase}/api/user/sector/users?setor=${valorSelecionado}`)

        if (!response?.success) {
            throw new Error(
                response.message ??
                response.error ??
                "Erro interno do servidor"
            );
        }



        let dataUser = response.data

        dataUser.forEach(item => {
            const card = createCardUser(templateCard, item);
            tableUser.appendChild(card);
        });

        hideLoading();

    } catch (error) {
        showModalToast(error, "erro")
    }

})

//Modal user

document.addEventListener('click', async (e) => {

    const card = e.target.closest('.card-item');
    if (!card) return;

    await debouncePromise(600);

    //Coleta ID de usuario
    const userId = card.dataset.id;

    try {
        response = await request(`${urlBase}/api/user/details?user_id=${userId}`)

        if (!response || response.success != true) {
            throw new Error(response?.message || "Erro interno no servidor");
        }

        dataUser = response.data

    } catch (error) {
        showModalToast(error, "erro")
    }


    const modal = showModalUser(dataUser)

    //Analizar clique no botão
    const btnEdit = modal.querySelector('.btn-editar');
    const btnFechar = modal.querySelector('.btn-close');
    const btnFrequencia = modal.querySelector('#btn-anexar-frequencia');

    //accordion
    const accordin = modal.querySelector('.accordion-header')
    const selectFolha = modal.querySelector('#select-folha-ponto')
    const containerTbody = modal.querySelector('#container-td')
    const containerPontoMensal = modal.querySelector('#tabela-folha-mensal')
    const templateTd = modal.querySelector('#template-tr-folha-mensal')

    createOptions(selectFolha, years)

    accordin.addEventListener('click', function () {
        document.querySelector('.accordion-item').classList.toggle('active')
    })

    //Botão para anexar atestado do usuario
    btnFrequencia.addEventListener('click', async () => {
        const modalCertificate = await showModalCertificate();

        //Libera input de origem para time
        const dataOrigem = modalCertificate.querySelector('#data-origem');
        const dataInicio = modalCertificate.querySelector('#data-inicio');
        const dataFim = modalCertificate.querySelector('#data-fim');
        const inputDias = modalCertificate.querySelector('#input-dias-atestados');
        const dropdown = modalCertificate.querySelector('.dropdown-justificativa')
        const inputArquivo = modalCertificate.querySelector('.btn-upload');

        //Manipulação de botões
        const btnConfirmar = modalCertificate.querySelector('.btn-confirmar');
        const BtnCancelar = modalCertificate.querySelector('.btn-cancelar');

        BtnCancelar.addEventListener('click', () => {
            modalCertificate.remove();
        })


        dataOrigem.type = 'date';
        RemoveReadonly(dataOrigem);

        dataOrigem.addEventListener('change', () => {
            // Data início recebe a mesma data da origem
            dataInicio.value = dataOrigem.value;
            dataInicio.value = formatDateBr(dataInicio.value);

            // Inicialmente data fim também
            dataFim.value = dataOrigem.value;
            dataFim.value = formatDateBr(dataOrigem.value);
        });

        inputDias.addEventListener('change', () => {
            const dias = Number(inputDias.value);

            if (!dataInicio.value) return;

            const dataFimValue = new Date(dataInicio.value);

            dataFimValue.setDate(dataFimValue.getDate() + (dias - 1));

            dataFim.value = dataFimValue.toISOString().split('T')[0];
        });

        btnConfirmar.addEventListener('click', async () => {

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

            modalCertificate.remove();

            //Converte dados para form
            formData.append('id', dataUser.id);
            formData.append('dateStart', dataInicio.value);
            formData.append('dateEnd', dataFim.value);
            formData.append('descricao_motivo', dropdown.value);
            formData.append('file', arquivo);

            try {
                response = await request(`${urlBase}/api/user/createAttachment`, {
                    method: "POST",
                    credentials: 'include',
                    body: formData
                })

                if (!response?.success) {
                    throw new Error(
                        response.error ??
                        response.message ??
                        "Erro interno do servidor"
                    );
                }

                //Apresenta modal
                showModalToast(response.data);

            } catch (error) {
                showModalToast(error, "error");
            }

        })
        document.body.appendChild(modalCertificate)

    })

    //Buscar Dados de folha de ponto

    const btnSearchFolhaMensal = modal.querySelector('#search-folha-mensal')

    let dadosFolhaMensal
    btnSearchFolhaMensal.addEventListener('click', async () => {

        await debouncePromise(900);

        let valorAnoFolha = parseInt(selectFolha.value, 10);
        let id = parseInt(dataUser.id, 10)

        containerTbody.innerHTML = '';

        containerPontoMensal.style.display = "inline-table";

        try {
            let response
            response = await request(`${urlBase}/api/user/${id}/timesheets?year=${valorAnoFolha}`
            )

            if (!response || response.success != true) {
                throw new Error(response?.message || "Erro interno no servidor");
            }

            dadosFolhaMensal = response.data

            //Cria elementos da tabela

            Object.values(dadosFolhaMensal).forEach((item) => {
                //Clona elementos
                const cloneTD = templateTd.content.cloneNode(true);
                const tdMonth = cloneTD.querySelector('[data-folha="month"]')
                const tdFile = cloneTD.querySelector('[data-folha="file"]')

                if (item.file) {
                    const link = document.createElement('a');
                    link.textContent = "Acessar Anexo"; // Corrigido o digito de "Axexo" :)
                    link.href = urlBase + item.file;
                    link.target = "_blank"; // Opcional: abre o PDF em uma nova aba do navegador

                    tdFile.appendChild(link);
                } else {
                    // Se não tiver arquivo, apenas injeta o texto puro na TD, sem link nenhum
                    tdFile.textContent = 'Sem registro';
                }

                //Renomeia TD month
                tdMonth.textContent = item.month

                containerTbody.appendChild(cloneTD);

            });

        } catch (error) {
            showModalToast(error, "erro")
        }
    })

    //Botão fechar
    btnFechar.addEventListener('click', () => {
        fecharModal();
        modal.remove();
    })

})

