document.addEventListener("DOMContentLoaded", async () => {
    const form = document.getElementById("form-detalhe");
    const btnExcluir = document.getElementById("btn-excluir");
    if (!form) return;

    const params = new URLSearchParams(window.location.search);
    const id = params.get("id");
    const dataURL = params.get("data");

    try {
        await carregarSelect("pacienteId", "listarPacientes", "nome");
        await carregarSelect("especialidadeId", "listarEspecialidades", "nome");
        await carregarSelect("medicoId", "listarMedicos", "nome");

        if (id) {
            await carregarConsulta(id);
        } else if (dataURL) {
            document.getElementById("data").value = dataURL;
        }
    } catch (err) {
        console.error("Erro inicial:", err);
    }

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const dataHora = sanitize(document.getElementById("data").value);
        const hora = sanitize(document.getElementById("hora").value);
        const pacienteId = sanitize(document.getElementById("pacienteId").value);
        const especialidadeId = sanitize(document.getElementById("especialidadeId").value);
        const medicoId = sanitize(document.getElementById("medicoId").value);
        const csrf_token = document.getElementById("csrf_token")?.value ?? "";

        if (!dataHora || !hora || !pacienteId || !especialidadeId || !medicoId) {
            alert("Preencha todos os campos.");
            return;
        }

        const dataCompleta = `${dataHora} ${hora}`;
        const consultaId = id ?? "";

        const dados = {
            id: consultaId,
            pacienteId,
            especialidadeId,
            medicoId,
            dataHora: dataCompleta,
            csrf_token
        };

        try {
            const acao = consultaId ? "atualizarConsulta" : "cadastrarConsulta";

            const response = await fetch(`/Software_Seguro/application/index.php?action=${acao}`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(dados)
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message);
            }

            alert("Consulta salva com sucesso.");
            window.location.href = "/Software_Seguro/application/index.php?pagina=agenda";
        } catch (err) {
            console.error("Erro ao salvar consulta:", err);
            alert("Erro ao salvar consulta.");
        }
    });

    btnExcluir.addEventListener("click", async () => {
        if (!id) {
            alert("Consulta não localizada.");
            return;
        }

        const confirmacao = confirm("Deseja realmente excluir esta consulta?");
        if (!confirmacao) return;

        const csrf_token = document.getElementById("csrf_token")?.value ?? "";

        try {
            const response = await fetch(`/Software_Seguro/application/index.php?action=removerConsulta`, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id, csrf_token })
            });

            const result = await response.json();

            if (!result.success) throw new Error(result.message);

            alert("Consulta removida com sucesso.");
            window.location.href = "/Software_Seguro/application/index.php?pagina=agenda";
        } catch (err) {
            console.error("Erro ao excluir consulta:", err);
            alert("Erro ao excluir consulta.");
        }
    });
});

async function carregarSelect(idSelect, action, propNome) {
    const select = document.getElementById(idSelect);
    if (!select) {
        console.warn(`Elemento ${idSelect} não encontrado.`);
        return;
    }

    try {
        const resp = await fetch(`/Software_Seguro/application/index.php?action=${action}`);
        const data = await resp.json();

        if (!data.success) throw new Error(data.message);

        data.data.forEach(item => {
            const opt = document.createElement("option");
            opt.value = item.id;
            opt.textContent = sanitize(item[propNome]);
            select.appendChild(opt);
        });
    } catch (err) {
        console.error(`Erro ao carregar ${idSelect}:`, err);
    }
}

async function carregarConsulta(id) {
    try {
        const resp = await fetch(`/Software_Seguro/application/index.php?action=buscarConsulta&id=${id}`);
        const data = await resp.json();

        if (!data.success) throw new Error(data.message);

        document.getElementById("id").value = data.data.id;
        document.getElementById("pacienteId").value = data.data.pacienteId;
        document.getElementById("especialidadeId").value = data.data.especialidadeId;
        document.getElementById("medicoId").value = data.data.medicoId;

        const dataHora = data.data.dataHora.split(" ");
        document.getElementById("data").value = dataHora[0];
        document.getElementById("hora").value = dataHora[1];
    } catch (err) {
        console.error("Erro ao carregar consulta:", err);
        alert("Consulta não encontrada.");
    }
}

function sanitize(texto) {
    const div = document.createElement("div");
    div.textContent = texto ?? '';
    return div.innerHTML;
}
