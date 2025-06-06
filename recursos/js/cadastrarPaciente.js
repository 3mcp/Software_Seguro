console.log("Script cadastrarPaciente.js carregado!");

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-paciente");

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    formData.append("acao", "cadastrar");

    fetch("../application/Controllers/PacienteController.php", {
      method: "POST",
      body: formData,
    })
      .then(res => res.json())
      .then(resposta => {
        if (resposta.sucesso) {
          alert("Paciente cadastrado com sucesso!");
          form.reset();
        } else {
          alert("Erro ao cadastrar paciente.");
        }
      })
      .catch(err => console.error("Erro ao cadastrar paciente:", err));
  });
});
