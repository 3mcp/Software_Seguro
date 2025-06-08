console.log("Script medicos.js carregado!");

document.addEventListener("DOMContentLoaded", function () {
  const tabela = document.getElementById("tabela-medico");
  if (!tabela) return;

  fetch("../application/Controllers/MedicoController.php?acao=listar")
    .then(res => res.json())
    .then(dados => {
      dados.forEach(medico => {
        const tr = document.createElement("tr");
        tr.setAttribute("data-id", medico.id);

        tr.innerHTML = `
          <td>${medico.nome}</td>
          <td>${medico.crm}</td>
          <td>${medico.especialidade}</td>
          <td>
            <button class="btn-excluir">Excluir</button>
          </td>
        `;

        tabela.appendChild(tr);
      });
    })
    .catch(err => console.error("Erro ao carregar médicos:", err));
});

document.addEventListener("click", async function (event) {
  const btnExcluir = event.target.closest(".btn-excluir");

  if (btnExcluir) {
    const tr = btnExcluir.closest("tr");
    const id = tr.dataset.id;

    if (confirm("Deseja realmente excluir este médico?")) {
      try {
        const res = await fetch("../application/Controllers/MedicoController.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ acao: "excluir", id })
        });

        const resposta = await res.json();
        if (resposta.sucesso) {
          alert("Médico excluído com sucesso!");
          location.reload();
        } else {
          alert(resposta.erro || "Erro ao excluir médico.");
        }
      } catch (err) {
        console.error("Erro na requisição de exclusão:", err);
        alert("Erro na comunicação com o servidor.");
      }
    }
  }
});
