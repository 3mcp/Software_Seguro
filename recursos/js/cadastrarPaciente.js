document.addEventListener("DOMContentLoaded", async () => {
    const formulario = document.querySelector("form-paciente");

    if (!formulario) {
        console.error("Formulário não encontrado.");
        return;
    }

    const id = new URLSearchParams(window.location.search).get("id");
    const csrfTokenInput = document.querySelector("#csrf_token");

    // Carregar CSRF Token (em segurança)
    async function carregarCSRF() {
        try {
            const resposta = await fetch("/Software_Seguro/utils/csrf_token.php");
            const dados = await resposta.json();
            csrfTokenInput.value = dados.token;
        } catch (err) {
            console.error("Erro ao carregar CSRF Token:", err);
        }
    }

    await carregarCSRF();

    // Se for edição, carregar os dados
    if (id) {
        try {
            const resposta = await fetch(`/Software_Seguro/application/index.php?action=buscarPaciente&id=${id}`);
            const dados = await resposta.json();

            if (dados.success) {
                document.querySelector("#id").value = dados.data.id;
                document.querySelector("#nome").value = dados.data.nome;
                document.querySelector("#cpf").value = dados.data.cpf;
                document.querySelector("#email").value = dados.data.email;
                document.querySelector("#telefone").value = dados.data.telefone;
                document.querySelector("#dataNascimento").value = dados.data.dataNascimento;
            } else {
                alert("Paciente não encontrado.");
                window.location.href = "/Software_Seguro/application/index.php?pagina=listagem-pacientes";
            }
        } catch (err) {
            console.error("Erro ao carregar paciente:", err);
        }
    }

    formulario.addEventListener("submit", async (e) => {
        e.preventDefault();

        const pacienteData = {
            id: document.querySelector("#id").value,
            nome: document.querySelector("#nome").value.trim(),
            cpf: document.querySelector("#cpf").value.trim(),
            email: document.querySelector("#email").value.trim(),
            telefone: document.querySelector("#telefone").value.trim(),
            dataNascimento: document.querySelector("#dataNascimento").value,
            csrf_token: csrfTokenInput.value
        };

        const url = pacienteData.id
            ? "/Software_Seguro/application/index.php?action=atualizarPaciente"
            : "/Software_Seguro/application/index.php?action=cadastrarPaciente";

        try {
            const resposta = await fetch(url, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(pacienteData)
            });

            const resultado = await resposta.json();

            if (resultado.success) {
                alert(resultado.message);
                window.location.href = "/Software_Seguro/application/index.php?pagina=listagem-pacientes";
            } else {
                alert("Erro: " + resultado.message);
            }
        } catch (err) {
            console.error("Erro ao enviar dados:", err);
            alert("Erro inesperado.");
        }
    });
});
