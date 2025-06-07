console.log("Script medicos.js carregado!");

document.addEventListener("DOMContentLoaded", function () {
  const tabela = document.getElementById("tabela-medico");
  if (!tabela) return;

  fetch("../application/Controllers/MedicoController.php?acao=listar")
    .then(res => res.json())
    .then(dados => {
      dados.forEach(medico => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
          <td>${medico.nome}</td>
          <td>${medico.crm}</td>
          <td>${medico.especialidade}</td>
          <td>
            <button class="btn-secundario btn-editar" data-id="${medico.id}">Editar</button>
            <button class="btn-excluir" data-id="${medico.id}">Excluir</button>
          </td>
        `;

        tabela.appendChild(tr);
      });
    })
    .catch(err => console.error("Erro ao carregar médicos:", err));
});

document.addEventListener("click", async function (event) {
  const editarBtn = event.target.closest(".btn-editar");
  const excluirBtn = event.target.closest(".btn-excluir");

  if (editarBtn) {
    const id = editarBtn.dataset.id;
    window.location.href = `?pagina=cadastro-medico&id=${id}`;
  }

  if (excluirBtn) {
    const id = excluirBtn.dataset.id;
    if (confirm("Deseja realmente excluir este médico?")) {
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
        alert(resposta.erro || "Erro ao excluir.");
      }
    }
  }
});

