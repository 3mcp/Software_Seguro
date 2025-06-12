document.addEventListener("DOMContentLoaded", () => {
  const formulario = document.getElementById("form-medico");
  const urlParams = new URLSearchParams(window.location.search);
  const medicoId = urlParams.get("id");
  let csrfToken = "";

  async function carregarCSRF() {
    try {
      const response = await fetch("/Software_Seguro/utils/csrf_token.php");
      const data = await response.json();
      csrfToken = data.token;
      document.getElementById("csrf_token").value = csrfToken;
    } catch (error) {
      console.error("Erro ao carregar CSRF Token:", error);
    }
  }

  async function carregarEspecialidades(selecionada = null) {
    try {
      const response = await fetch("/Software_Seguro/application/index.php?action=listarEspecialidades");
      const resultado = await response.json();
      if (!resultado.success || !resultado.data) throw new Error("Erro ao carregar especialidades");

      const select = document.getElementById("especialidade");
      resultado.data.forEach((especialidade) => {
        const option = document.createElement("option");
        option.value = especialidade.id;
        option.textContent = especialidade.nome;
        if (especialidade.id == selecionada) option.selected = true;
        select.appendChild(option);
      });
    } catch (error) {
      console.error("Erro ao carregar especialidades:", error);
    }
  }

  async function carregarMedico(id) {
    try {
      const response = await fetch(`/Software_Seguro/application/index.php?action=buscarMedico&id=${id}`);
      const resultado = await response.json();
      if (!resultado.success || !resultado.data) throw new Error("Erro ao buscar médico");

      const medico = resultado.data;
      document.getElementById("id").value = medico.id;
      document.getElementById("nome").value = medico.nome;
      document.getElementById("crm").value = medico.crm;
      await carregarEspecialidades(medico.especialidadeId);
    } catch (error) {
      console.error("Erro ao carregar dados do médico:", error);
    }
  }

  formulario.addEventListener("submit", async (event) => {
    event.preventDefault();

    const dados = {
      id: document.getElementById("id").value,
      nome: document.getElementById("nome").value.trim(),
      crm: document.getElementById("crm").value.trim(),
      especialidadeId: document.getElementById("especialidade").value,
      csrf_token: csrfToken
    };

    const action = dados.id ? "atualizarMedico" : "cadastrarMedico";

    try {
      const response = await fetch(`/Software_Seguro/application/index.php?action=${action}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(dados)
      });

      const resultado = await response.json();
      if (resultado.success) {
        alert("Médico salvo com sucesso.");
        window.location.href = "/Software_Seguro/application/index.php?pagina=listagem-medicos";
      } else {
        alert("Erro: " + resultado.message);
      }
    } catch (error) {
      console.error("Erro ao enviar dados:", error);
      alert("Erro ao processar dados do médico.");
    }
  });

  carregarCSRF();

  if (medicoId) {
    document.getElementById("id").value = medicoId;
    carregarMedico(medicoId);
  } else {
    carregarEspecialidades();
  }
});
