console.log("Script pacientes.js carregado!");

document.addEventListener("DOMContentLoaded", function () {
  const corpoTabela = document.getElementById("corpo-tabela-pacientes");

  if (!corpoTabela) return;

  fetch("../application/Controllers/PacienteController.php?acao=listar")
    .then((res) => res.json())
    .then((dados) => {
      dados.forEach((paciente) => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
          <td>${paciente.nome}</td>
          <td>${paciente.email}</td>
          <td>${paciente.telefone}</td>
          <td>${paciente.dataNascimento}</td>
          <td>
            <button class="btn-excluir" data-id="${paciente.id}">Excluir</button>
          </td>
        `;

        corpoTabela.appendChild(tr);
      });

      document.querySelectorAll(".btn-excluir").forEach((botao) => {
        botao.addEventListener("click", async function () {
          const id = this.dataset.id;

          if (confirm("Tem certeza que deseja excluir este paciente?")) {
            const res = await fetch("../application/Controllers/PacienteController.php", {
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
              alert("Erro ao excluir paciente.");
            }
          }
        });
      });
    })
    .catch((err) => console.error("Erro ao carregar pacientes:", err));
});
