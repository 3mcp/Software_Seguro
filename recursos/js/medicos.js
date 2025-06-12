document.addEventListener("DOMContentLoaded", async () => {
    const tabela = document.getElementById("tabela-medicos");
    if (!tabela) return;

    try {
        const resp = await fetch("/Software_Seguro/application/index.php?action=listarMedicos");
        const data = await resp.json();

        if (!data.success) throw new Error(data.message);

        const tbody = tabela.querySelector("tbody");
        tbody.innerHTML = "";

        data.data.forEach(medico => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${sanitize(medico.nome)}</td>
                <td>${sanitize(medico.crm)}</td>
                <td>${sanitize(medico.nomeEspecialidade)}</td>
                <td>
                    <button class="btn-editar" data-id="${medico.id}">Editar</button>
                    <button class="btn-excluir" data-id="${medico.id}">Excluir</button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        adicionarListeners();
    } catch (err) {
        console.error("Erro ao carregar médicos:", err);
    }
});

function sanitize(texto) {
    const div = document.createElement("div");
    div.textContent = texto ?? '';
    return div.innerHTML;
}

function adicionarListeners() {
    document.querySelectorAll(".btn-editar").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            window.location.href = `/Software_Seguro/application/index.php?pagina=cadastro-medico&id=${id}`;
        });
    });

    document.querySelectorAll(".btn-excluir").forEach(btn => {
        btn.addEventListener("click", async () => {
            if (!confirm("Tem certeza que deseja excluir este médico?")) return;

            try {
                const csrfToken = await obterCsrfToken();
                const resp = await fetch("/Software_Seguro/application/index.php?action=removerMedico", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id: btn.dataset.id, csrf_token: csrfToken })
                });

                const data = await resp.json();
                if (!data.success) throw new Error(data.message);

                alert("Médico excluído com sucesso!");
                location.reload();
            } catch (err) {
                console.error("Erro ao excluir médico:", err);
                alert("Erro ao excluir médico.");
            }
        });
    });
}

async function obterCsrfToken() {
    const resp = await fetch("/Software_Seguro/utils/csrf_token.php");
    const data = await resp.json();
    return data.token;
}
