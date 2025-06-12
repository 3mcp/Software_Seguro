document.addEventListener("DOMContentLoaded", () => {
    const tabela = document.getElementById("tabela-pacientes");

    if (!tabela) {
        console.warn("Tabela de pacientes não encontrada.");
        return;
    }

    carregarPacientes();

    async function carregarPacientes() {
        try {
            const response = await fetch("/Software_Seguro/application/index.php?action=listarPacientes");
            const dados = await response.json();

            if (!dados.success) throw new Error(dados.message);

            const tbody = tabela.querySelector("tbody");
            tbody.innerHTML = "";

            dados.data.forEach(paciente => {
                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <td>${sanitize(paciente.nome)}</td>
                    <td>${sanitize(paciente.email)}</td>
                    <td>${sanitize(paciente.telefone)}</td>
                    <td>${sanitize(paciente.dataNascimento)}</td>
                    <td>
                        <button class="btn-editar" data-id="${paciente.id}">Editar</button>
                        <button class="btn-excluir" data-id="${paciente.id}">Excluir</button>
                    </td>
                `;

                tbody.appendChild(tr);
            });

            adicionarListeners();
        } catch (err) {
            console.error("Erro ao carregar pacientes:", err);
            alert("Falha ao carregar pacientes.");
        }
    }

    function adicionarListeners() {
        document.querySelectorAll(".btn-editar").forEach(botao => {
            botao.addEventListener("click", () => {
                const id = botao.dataset.id;
                window.location.href = `/Software_Seguro/application/index.php?pagina=cadastro-paciente&id=${id}`;
            });
        });

        document.querySelectorAll(".btn-excluir").forEach(botao => {
            botao.addEventListener("click", async () => {
                const id = botao.dataset.id;

                if (!confirm("Deseja realmente excluir este paciente?")) return;

                const csrf_token = await buscarCsrf();

                try {
                    const resp = await fetch("/Software_Seguro/application/index.php?action=removerPaciente", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ id, csrf_token })
                    });

                    const result = await resp.json();

                    if (!result.success) throw new Error(result.message);

                    alert("Paciente removido com sucesso!");
                    carregarPacientes();
                } catch (err) {
                    console.error("Erro ao excluir paciente:", err);
                    alert("Falha ao excluir paciente.");
                }
            });
        });
    }

    async function buscarCsrf() {
        try {
            const resp = await fetch("/Software_Seguro/utils/csrf_token.php");
            const data = await resp.json();
            return data.token;
        } catch {
            return "";
        }
    }

    function sanitize(texto) {
        const div = document.createElement("div");
        div.textContent = texto ?? "";
        return div.innerHTML;
    }
});
