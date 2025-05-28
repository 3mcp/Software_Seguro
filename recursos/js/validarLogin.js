document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".formLogin");

  form.addEventListener("submit", function (e) {
    const usuario = document.getElementById("usuario").value.trim();
    const senha = document.getElementById("senha").value.trim();

    if (usuario === " ") {
      alert("Por favor, preencha o campo de usuário.");
      e.preventDefault();
      return;
    }

    if (senha === " ") {
      alert("Por favor, preencha o campo de senha.");
      e.preventDefault();
      return;
    }
  });
});
