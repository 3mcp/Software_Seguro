document.addEventListener('DOMContentLoaded', async function () {
    try {
        // Requisição para obter o token CSRF
        const response = await fetch('/Software_Seguro/utils/csrf_token.php');
        if (!response.ok) throw new Error('Erro ao obter token CSRF');

        // Extrai o token da resposta JSON
        const data = await response.json();
        const token = data.token;

        // Armazena em sessionStorage (opcional, caso queira reutilizar)
        sessionStorage.setItem('csrf_token', token);

        // Insere o token no campo do formulário, se existir
        const csrfField = document.querySelector('input[name="csrf_token"]');
        if (csrfField) {
            csrfField.value = token;
        }

    } catch (error) {
        console.error("Erro ao configurar CSRF:", error);
    }
});

