document.addEventListener("DOMContentLoaded", async () => {
    const tabela = document.getElementById("tabela-especialidades");
    if (!tabela) return;

    try {
        const resp = await fetch("/Software_Seguro/application/index.php?action=listarEspecialidades");
        const data = await resp.json();

        if (!data.success) throw new Error(data.message);

        const tbody = tabela.querySelector("tbody");
        tbody.innerHTML = "";

        data.data.forEach(especialidade => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${sanitize(especialidade.nome)}</td>
                <td>
                    <button class="btn-editar" data-id="${especialidade.id}">Editar</button>
                    <button class="btn-excluir" data-id="${especialidade.id}">Excluir</button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        adicionarListeners();
    } catch (err) {
        console.error("Erro ao carregar especialidades:", err);
    }
});

// Sanitização básica contra XSS
function sanitize(texto) {
    const div = document.createElement("div");
    div.textContent = texto ?? '';
    return div.innerHTML;
}

function adicionarListeners() {
    document.querySelectorAll(".btn-editar").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            window.location.href = `/Software_Seguro/application/index.php?pagina=cadastro-especialidade&id=${id}`;
        });
    });

    document.querySelectorAll(".btn-excluir").forEach(btn => {
        btn.addEventListener("click", async () => {
            if (!confirm("Tem certeza que deseja excluir esta especialidade?")) return;

            try {
                const csrfToken = await obterCsrfToken();
                const resp = await fetch("/Software_Seguro/application/index.php?action=removerEspecialidade", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: btn.dataset.id, csrf_token: csrfToken })
                });

                const data = await resp.json();
                if (!data.success) throw new Error(data.message);

                alert("Especialidade excluída com sucesso!");
                location.reload();
            } catch (err) {
                console.error("Erro ao excluir especialidade:", err);
                alert("Erro ao excluir especialidade.");
            }
        });
    });
}

// Função auxiliar global de obtenção do CSRF
async function obterCsrfToken() {
    const resp = await fetch("/Software_Seguro/utils/csrf_token.php");
    const data = await resp.json();
    return data.token;
}
