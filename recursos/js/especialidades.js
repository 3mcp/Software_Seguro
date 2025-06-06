console.log("Script especialidades.js carregado!");

document.addEventListener("DOMContentLoaded", function () {
  const tabela = document.getElementById("tabela-especialidades");
  if (!tabela) return;

  fetch("../application/Controllers/EspecialidadeController.php?acao=listar")
    .then(res => res.json())
    .then(dados => {
      dados.forEach(item => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
          <td>${item.nome}</td>
        `;

        tabela.querySelector("tbody").appendChild(tr);
      });
    })
    .catch(err => console.error("Erro ao carregar especialidades:", err));
});
