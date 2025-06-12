document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form-especialidade");
    const idInput = document.getElementById("id");
    const nomeInput = document.getElementById("nome");
    let csrfToken = "";

    async function carregarCSRF() {
        try {
            const resposta = await fetch("/Software_Seguro/utils/csrf_token.php");
            const dados = await resposta.json();
            csrfToken = dados.token;
            document.getElementById("csrf_token").value = csrfToken;
        } catch (err) {
            console.error("Erro ao buscar CSRF:", err);
        }
    }

    async function carregarEspecialidade(id) {
        try {
            const resposta = await fetch(`/Software_Seguro/application/index.php?action=buscarEspecialidade&id=${id}`);
            const dados = await resposta.json();

            if (dados.success) {
                nomeInput.value = dados.data.nome;
            } else {
                alert("Especialidade não encontrada.");
            }
        } catch (err) {
            console.error("Erro ao buscar especialidade:", err);
        }
    }

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const nome = nomeInput.value.trim();
        const id = idInput.value;

        if (nome.length < 3) {
            alert("O nome da especialidade deve conter no mínimo 3 caracteres.");
            return;
        }

        const payload = {
            id: id,
            nome: nome,
            csrf_token: csrfToken
        };

        const action = id ? "atualizarEspecialidade" : "cadastrarEspecialidade";

        try {
            const resposta = await fetch(`/Software_Seguro/application/index.php?action=${action}`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            });

            const resultado = await resposta.json();

            if (resultado.success) {
                alert(resultado.message);
                window.location.href = "/Software_Seguro/application/index.php?pagina=listagem-especialidades";
            } else {
                alert("Erro: " + resultado.message);
            }
        } catch (err) {
            console.error("Erro ao enviar dados:", err);
        }
    });

    carregarCSRF();

    // Verifica se é edição
    const urlParams = new URLSearchParams(window.location.search);
    const idEspecialidade = urlParams.get("id");
    if (idEspecialidade) {
        idInput.value = idEspecialidade;
        carregarEspecialidade(idEspecialidade);
    }
});
