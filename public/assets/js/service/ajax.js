//Função Global de AJAX
export async function request(url, options = {}) {
    try {
        const response = await fetch(url, {
            method: options.method || 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
            body: options.body ? JSON.stringify(options.body) : null
        });

        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }

        //Espera resposta da PROMISE acima
        const data = await response.json();

        return data;

    } catch (error) {

        return {
            success: false,
            error: error.message
        };
    }

}