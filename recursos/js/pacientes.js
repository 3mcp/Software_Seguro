console.log("Script pacientes.js carregado!");
document.addEventListener("DOMContentLoaded", function () {
  const tabela = document.getElementById("tabela-pacientes");

  if (!tabela) return;

  fetch("/Software_Seguro/application/Controllers/PacienteController.php?acao=listar")
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
            <button class="btn-secundario">Editar</button>
            <button class="btn-excluir">Excluir</button>
          </td>
        `;

        tabela.querySelector("tbody").appendChild(tr);
      });
    })
    .catch((err) => console.error("Erro ao carregar pacientes:", err));
});
