//Função Global de AJAX
export async function request(url, options = {}) {
    try {
        const isFormData = options.body instanceof FormData;

        const response = await fetch(url, {
            method: options.method || 'GET',
            credentials: options.credentials,
            headers: isFormData
                ? { ...options.headers }
                : {
                    'Content-Type': 'application/json',
                    ...options.headers
                },
            body: options.body
                ? isFormData
                    ? options.body
                    : JSON.stringify(options.body)
                : null
        });

        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }

        //Espera resposta da PROMISE acima
        const data = await response.json();
 
         return data;

        /* const text = await response.text();

        console.log(text);

        const data = JSON.parse(text); 

        return data; */

    } catch (error) {

        return {
            success: false,
            error: error.message
        };
    }

}