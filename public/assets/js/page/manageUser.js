import { request } from "../service/ajax.js";
import { apresentarModal } from "../utils/modal.js";
import { showLoading, hideLoading } from "../utils/loading.js";
import { debouncePromise, delay } from "../utils/delay.js";
import { getFormData } from "../utils/form.js";
import { createOptions } from "../utils/createoptions.js";

//Carrega anos validos
let years;
try {
    let response
    response = await request(`${urlBase}/api/attendance/available-periods`)

    if (!response || response.success != true) {
        throw new Error(response?.error);
    }

    //Separa variaveis de ano e mes
    years = response.data.years;

} catch (error) {
    console.log("Erro de comunicação")
}
//Monta select de anos fixo
const selectFolhaPonto = document.querySelector('#select-folha-ponto');
createOptions(selectFolhaPonto, years)


//Coleta setores Registrados no sistema
let optionSetor
let optionUser
try {
    let response
    response = await request(`${urlBase}/api/user/sectors`)

    if (!response || response.success != true) {
        throw new Error(response?.message || "Erro interno no servidor");
    }
    optionSetor = response.data

} catch (error) {
    console.log("Erro de comunicação")
}

//Gera options para selectSetor
const selectSetor = document.querySelector('#dropdown-setor')
const selectUser = document.querySelector('#dropdown-pessoas')
const containerUsers = document.querySelector('#container-pessoas')

createOptions(selectSetor, optionSetor)

//Selecionar setor selecionado
selectSetor.addEventListener('change', async (event) => {
    //Libera container users
    containerUsers.style.display = "flex";

    //Pega Valor da Seleção escolhida
    const valorSelecionado = event.target.value;

    //Tempo debounce para impedir varias requisições
    await debouncePromise(500);
    selectUser.innerHTML = "";

    try {
        let response
        response = await request(`${urlBase}/api/user/sector/users?setor=${valorSelecionado}`)

        if (!response || response.success != true) {
            throw new Error(response?.message || "Erro interno no servidor");
        }

        optionUser = response.data
        //Gera options para selectUser
        createOptions(selectUser, optionUser)

    } catch (error) {
        console.log("Erro de comunicação")
    }
})

//Buscar de Todos os dados do usuario
const btnSearchUser = document.querySelector('#btn-search-user')
const containerDadosUser = document.querySelector('#container-user-dados')
let DadosUser

//Container de folhas mensal
const containerPontoMensal = document.querySelector('#tabela-folha-mensal')
const containerTbody = document.querySelector('#container-td')
const templateTd = document.querySelector('#template-tr-folha-mensal')

//Coleta de Elementos de tabela
const caledarioTitulo = document.querySelector('.calendario-titulo span');


btnSearchUser.addEventListener('click', async () => {
    //Carrega animação
    showLoading();

    containerDadosUser.style.display = "block";

    //Limpa tabela de dados
    caledarioTitulo.textContent = "";
    document.querySelector('.accordion-item').classList.remove('active')
    containerTbody.textContent = "";
    containerPontoMensal.style.display = "none";

    await debouncePromise(600);

    const userId = selectUser.value;

    try {
        let response
        response = await request(`${urlBase}/api/user/details?user_id=${userId}`)

        if (!response || response.success != true) {
            throw new Error(response?.message || "Erro interno no servidor");
        }

        DadosUser = response.data

    } catch (error) {
        console.log("Erro de comunicação")
    }
    await setTimeout(() => {
        hideLoading();
    }, 1500);

    caledarioTitulo.textContent = `Ficha de ${DadosUser.nome}`;

    //Armazenamento dinamico de dados de tabela
    Object.entries(DadosUser).forEach(([index, valor]) => {
        const celula = document.querySelector(`td[data-item="${index}"]`)
        //Proteção para evitar erros se a célula não existir no HTML
        if (celula) {
            celula.textContent = valor;
        }
    });
})

//Analizar Clique do accordin
const accordin = document.querySelector('.accordion-header')
accordin.addEventListener('click', function () {
    document.querySelector('.accordion-item').classList.toggle('active')
})

//Buscar dados de folhas Mensal
const selecFolhaPonto = document.querySelector('#select-folha-ponto')
const btnSearchFolhaMensal = document.querySelector('#search-folha-mensal')

let dadosFolhaMensal
btnSearchFolhaMensal.addEventListener('click', async () => {

    await debouncePromise(900);

    let valorAnoFolha = parseInt(selecFolhaPonto.value, 10);
    let id = parseInt(DadosUser.id, 10)

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
                link.href = item.file;
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
        console.log(error)
    }
})









