console.log("Script cadastrarMedico.js carregado!");

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-medico");

  // Carrega especialidades no select
  fetch("../application/Controllers/EspecialidadeController.php?acao=listar")
    .then(res => res.json())
    .then(dados => {
      const select = document.getElementById("especialidade");
      dados.forEach(especialidade => {
        const option = document.createElement("option");
        option.value = especialidade.id;
        option.textContent = especialidade.nome;
        select.appendChild(option);
      });
    });

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    formData.append("acao", "cadastrar");

    fetch("../application/Controllers/MedicoController.php", {
      method: "POST",
      body: formData,
    })
      .then(res => res.json())
      .then(resposta => {
        if (resposta.sucesso) {
          alert("Médico cadastrado com sucesso!");
          form.reset();
        } else {
          alert("Erro ao cadastrar médico.");
          console.error(resposta.erro);
        }
      })
      .catch(err => {
        console.error("Erro na requisição:", err);
      });
  });
});
