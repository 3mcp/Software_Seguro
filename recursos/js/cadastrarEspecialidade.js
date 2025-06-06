console.log("Script cadastrarEspecialidade.js carregado!");

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-especialidade");

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    formData.append("acao", "cadastrar");

    fetch("../application/Controllers/EspecialidadeController.php", {
      method: "POST",
      body: formData,
    })
      .then(res => res.json())
      .then(resposta => {
        if (resposta.sucesso) {
          alert("Especialidade cadastrada com sucesso!");
          form.reset();
        } else {
          alert("Erro ao cadastrar especialidade.");
        }
      })
      .catch(err => console.error("Erro ao cadastrar especialidade:", err));
  });
});
