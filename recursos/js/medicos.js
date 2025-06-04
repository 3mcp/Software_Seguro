console.log("Script medicos.js carregado!");

document.addEventListener("DOMContentLoaded", function () {
  const tabela = document.getElementById("tabela-medico");
  if (!tabela) return;

  fetch("/Software_Seguro/application/Controllers/MedicoController.php?acao=listar")
    .then(res => res.json())
    .then(dados => {
      dados.forEach(medico => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
          <td>${medico.nome}</td>
          <td>${medico.crm}</td>
          <td>${medico.especialidade}</td>
          <td>
            <button class="btn-secundario">Editar</button>
            <button class="btn-excluir">Excluir</button>
          </td>
        `;

        tabela.appendChild(tr);
      });
    })
    .catch(err => console.error("Erro ao carregar médicos:", err));
});
