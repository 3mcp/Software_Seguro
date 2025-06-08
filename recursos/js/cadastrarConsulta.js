document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("form-cadastro-consulta");

  const pacienteSelect = document.getElementById("paciente");
  const medicoSelect = document.getElementById("medico");
  const especialidadeSelect = document.getElementById("especialidade");

  async function carregarSelect(idSelect, url, chaveId = "id", chaveTexto = "nome") {
    const select = document.getElementById(idSelect);
    if (!select) return;

    try {
      const resposta = await fetch(url);
      const dados = await resposta.json();

      dados.forEach((item) => {
        const option = document.createElement("option");
        option.value = item[chaveId];
        option.textContent = item[chaveTexto];
        select.appendChild(option);
      });
    } catch (erro) {
      console.error(`Erro ao carregar ${idSelect}:`, erro);
    }
  }

  carregarSelect("paciente", "../application/Controllers/PacienteController.php?acao=listar");
  carregarSelect("medico", "../application/Controllers/MedicoController.php?acao=listar");
  // ❌ REMOVA O carregamento de especialidade no início

  // 🔁 Atualizar especialidade com base no médico selecionado
  medicoSelect.addEventListener("change", async function () {
    const medicoId = this.value;

    // Limpa e desativa o select
    especialidadeSelect.innerHTML = "";
    especialidadeSelect.disabled = true;

    if (!medicoId) return;

    try {
      const res = await fetch(`../application/Controllers/MedicoController.php?acao=buscarEspecialidade&medicoId=${medicoId}`);
      const especialidade = await res.json();

      if (especialidade && especialidade.id) {
        const option = document.createElement("option");
        option.value = especialidade.id;
        option.textContent = especialidade.nome;
        especialidadeSelect.appendChild(option);
        especialidadeSelect.disabled = false; // Ou mantenha true se quiser bloquear edição
      } else {
        const option = document.createElement("option");
        option.value = "";
        option.textContent = "Especialidade não encontrada";
        especialidadeSelect.appendChild(option);
      }
    } catch (erro) {
      console.error("Erro ao buscar especialidade:", erro);
    }
  });

  // Envio do formulário
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();

      const dados = {
        pacienteId: pacienteSelect.value,
        medicoId: medicoSelect.value,
        especialidadeId: especialidadeSelect.value,
        dataHora: document.getElementById("data").value + " " + document.getElementById("hora").value,
      };

      const payload = { acao: "atualizar", ...dados };

      try {
        const resposta = await fetch("../application/Controllers/ConsultaController.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });

        const resultado = await resposta.json();

        if (resultado.sucesso) {
          alert("Consulta cadastrada com sucesso!");
          window.location.href = "?pagina=dashboard";
        } else {
          alert("Erro ao cadastrar consulta: " + (resultado.erro || "Erro desconhecido."));
        }
      } catch (erro) {
        console.error("Erro ao enviar dados:", erro);
        alert("Erro inesperado ao cadastrar consulta.");
      }
    });
  }
});
