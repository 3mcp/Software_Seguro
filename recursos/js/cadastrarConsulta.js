document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("form-cadastro-consulta");
  const pacienteSelect = document.getElementById("paciente");
  const medicoSelect = document.getElementById("medico");
  const especialidadeSelect = document.getElementById("especialidade");

  // Função genérica para carregar opções em <select>
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

  // Carrega pacientes e médicos no carregamento da página
  carregarSelect("paciente", "../application/Controllers/PacienteController.php?acao=listar");
  carregarSelect("medico", "../application/Controllers/MedicoController.php?acao=listar");

  // Atualiza especialidade quando o médico for selecionado
  medicoSelect.addEventListener("change", async function () {
    const medicoId = this.value;
    especialidadeSelect.innerHTML = "";
    especialidadeSelect.disabled = true;

    if (!medicoId) return;

    try {
      const res = await fetch(`../application/Controllers/MedicoController.php?acao=buscarEspecialidade&medicoId=${medicoId}`);
      const especialidade = await res.json();

      const option = document.createElement("option");

      if (especialidade && especialidade.id) {
        option.value = especialidade.id;
        option.textContent = especialidade.nome;
        especialidadeSelect.disabled = false;
      } else {
        option.value = "";
        option.textContent = "Especialidade não encontrada";
      }

      especialidadeSelect.appendChild(option);
    } catch (erro) {
      console.error("Erro ao buscar especialidade:", erro);
    }
  });

  // Envia o formulário de cadastro da consulta
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();

      // Captura e formata data e hora
      const data = document.getElementById("data").value;
      const hora = document.getElementById("hora").value;
      const dataObj = new Date(`${data} ${hora}`);
      const dataHoraFormatada = dataObj.toISOString().slice(0, 19).replace("T", " ");

      const dados = {
        pacienteId: pacienteSelect.value,
        medicoId: medicoSelect.value,
        especialidadeId: especialidadeSelect.value,
        dataHora: dataHoraFormatada
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
