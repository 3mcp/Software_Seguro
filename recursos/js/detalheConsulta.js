document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("form-detalhe");
  const btnExcluir = document.getElementById("btn-excluir");

  const urlParams = new URLSearchParams(window.location.search);
  const consultaId = urlParams.get("id");

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

  // Carrega os dados nos selects e depois carrega os dados da consulta
  Promise.all([
    carregarSelect("paciente", "../application/Controllers/PacienteController.php?acao=listar"),
    carregarSelect("medico", "../application/Controllers/MedicoController.php?acao=listar"),
    carregarSelect("especialidade", "../application/Controllers/EspecialidadeController.php?acao=listar")
  ]).then(() => {
    buscarConsulta();
  });

  async function buscarConsulta() {
    try {
      const resposta = await fetch("../application/Controllers/ConsultaController.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ acao: "buscar", id: consultaId })
      });

      const consulta = await resposta.json();

      document.getElementById("paciente").value = consulta.pacienteId;
      document.getElementById("medico").value = consulta.medicoId;
      document.getElementById("especialidade").value = consulta.especialidadeId;
      document.getElementById("data").value = consulta.dataHora.split(" ")[0];
      document.getElementById("hora").value = consulta.dataHora.split(" ")[1].slice(0, 5);

      document.getElementById("paciente").disabled = true;
      document.getElementById("medico").disabled = true;
      document.getElementById("especialidade").disabled = true;
    } catch (erro) {
      console.error("Erro ao buscar consulta:", erro);
      alert("Erro ao buscar dados da consulta.");
    }
  }

  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    const dados = {
      acao: "editar",
      id: consultaId,
      dataHora: document.getElementById("data").value + " " + document.getElementById("hora").value,
    };

    try {
      const resposta = await fetch("../application/Controllers/ConsultaController.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(dados),
      });

      const resultado = await resposta.json();

      if (resultado.sucesso) {
        alert("Consulta atualizada com sucesso!");
        window.location.href = "?pagina=dashboard";
      } else {
        alert("Erro ao atualizar consulta: " + resultado.erro);
      }
    } catch (erro) {
      console.error("Erro ao atualizar consulta:", erro);
      alert("Erro na comunicação com o servidor.");
    }
  });

  btnExcluir.addEventListener("click", async function () {
    if (confirm("Tem certeza que deseja excluir esta consulta?")) {
      try {
        const resposta = await fetch("../application/Controllers/ConsultaController.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ acao: "excluir", id: consultaId }),
        });

        const resultado = await resposta.json();

        if (resultado.sucesso) {
          alert("Consulta excluída com sucesso!");
          window.location.href = "?pagina=dashboard";
        } else {
          alert("Erro ao excluir consulta: " + resultado.erro);
        }
      } catch (erro) {
        console.error("Erro ao excluir consulta:", erro);
        alert("Erro na comunicação com o servidor.");
      }
    }
  });
});
