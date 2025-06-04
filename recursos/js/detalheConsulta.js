document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("form-detalhe");

  // Função para popular os selects
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

  // Carrega pacientes, médicos e especialidades
  carregarSelect("paciente", "/Software_Seguro/application/Controllers/PacienteController.php?acao=listar");
  carregarSelect("medico", "/Software_Seguro/application/Controllers/MedicoController.php?acao=listar");
  carregarSelect("especialidade", "/Software_Seguro/application/Controllers/EspecialidadeController.php?acao=listar");

  // Evento de submit do formulário
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();

      const dados = {
        pacienteId: document.getElementById("paciente").value,
        medicoId: document.getElementById("medico").value,
        especialidadeId: document.getElementById("especialidade").value,
        dataHora:
          document.getElementById("data").value + " " + document.getElementById("hora").value,
      };

      try {
        const resposta = await fetch("/Software_Seguro/application/Controllers/ConsultaController.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ acao: "atualizar", ...dados }),
        });

        const resultado = await resposta.json();

        if (resultado.sucesso) {
          alert("Consulta atualizada com sucesso!");
          window.location.href = "?pagina=dashboard";
        } else {
          alert("Erro ao atualizar consulta: " + resultado.erro);
        }
      } catch (erro) {
        console.error("Erro ao enviar dados:", erro);
        alert("Erro na comunicação com o servidor.");
      }
    });
  }
});
