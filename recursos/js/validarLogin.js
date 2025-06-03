document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".formLogin");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    const usuario = document.getElementById("usuario").value.trim();
    const senha = document.getElementById("senha").value.trim();

    if (!usuario) {
      alert("O campo de usuário é obrigatório.");
      e.preventDefault();
      return;
    }

    if (!senha) {
      alert("O campo de senha é obrigatório.");
      e.preventDefault();
      return;
    }

    if (usuario.length < 3 || usuario.length > 30) {
      alert("O nome de usuário deve ter entre 3 e 30 caracteres.");
      e.preventDefault();
      return;
    }

    // Prevenção básica contra XSS
    const xssPattern = /[<>"'`]/;
    if (xssPattern.test(usuario) || xssPattern.test(senha)) {
      alert("Caracteres inválidos detectados.");
      e.preventDefault();
    }
  });
});

