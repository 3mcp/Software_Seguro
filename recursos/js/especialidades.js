console.log("Script especialidades.js carregado!");

document.addEventListener("DOMContentLoaded", function () {
  const tabela = document.getElementById("tabela-especialidades");

  if (!tabela) return;

  fetch("../application/Controllers/EspecialidadeController.php?acao=listar")
    .then((res) => res.json())
    .then((dados) => {
      dados.forEach((especialidade) => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
          <td>${especialidade.nome}</td>
          <td>
            <button class="btn-secundario">Editar</button>
            <button class="btn-excluir" data-id="${especialidade.id}">Excluir</button>
          </td>
        `;

        tabela.appendChild(tr);
      });

      // Evento de exclusão
      document.querySelectorAll(".btn-excluir").forEach((btn) => {
        btn.addEventListener("click", async function () {
          const id = this.dataset.id;

          if (confirm("Tem certeza que deseja excluir esta especialidade?")) {
            const res = await fetch("../application/Controllers/EspecialidadeController.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/x-www-form-urlencoded"
              },
              body: `acao=excluir&id=${id}`
            });

            const resultado = await res.json();

            if (resultado.sucesso) {
              this.closest("tr").remove();
            } else {
              alert("Erro ao excluir especialidade.");
            }
          }
        });
      });
    })
    .catch((err) => console.error("Erro ao carregar especialidades:", err));
});
