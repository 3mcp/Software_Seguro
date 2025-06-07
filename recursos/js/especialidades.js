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
          try {
            const res = await fetch("../application/Controllers/EspecialidadeController.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/x-www-form-urlencoded"
              },
              body: `acao=excluir&id=${id}`
            });

            console.log("teste");

            const resultado = await res.json();
            console.log("Resposta recebida:", resultado);

            if (resultado.sucesso) {
              this.closest("tr").remove();
            } else if (resultado.erro) {
              alert(resultado.erro);
            } else {
              alert("Erro ao excluir especialidade.");
            }

          } catch (err) {
            console.error("Erro ao processar a resposta:", err);
            alert("Erro inesperado ao excluir especialidade.");
          }
        }
      });
      });
    })
    .catch((err) => console.error("Erro ao listar especialidades:", err));

});
