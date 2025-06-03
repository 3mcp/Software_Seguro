console.log("Script validarLogin.js carregado!");

async function sha256(texto) {
  const encoder = new TextEncoder();
  const data = encoder.encode(texto);
  const hashBuffer = await crypto.subtle.digest('SHA-256', data);
  return Array.from(new Uint8Array(hashBuffer))
    .map(b => b.toString(16).padStart(2, '0'))
    .join('');
}

document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".formLogin");
  if (!form) return;

  form.addEventListener("submit", async function (e) {
    const usuario = document.getElementById("usuario").value.trim();
    const senhaInput = document.getElementById("senha");

    if (!usuario) {
      alert("O campo de usuário é obrigatório.");
      e.preventDefault();
      return;
    }

    if (!senhaInput.value.trim()) {
      alert("O campo de senha é obrigatório.");
      e.preventDefault();
      return;
    }

    if (usuario.length < 3 || usuario.length > 30) {
      alert("O nome de usuário deve ter entre 3 e 30 caracteres.");
      e.preventDefault();
      return;
    }

    const xssPattern = /[<>"'`]/;
    if (xssPattern.test(usuario) || xssPattern.test(senhaInput.value)) {
      alert("Caracteres inválidos detectados.");
      e.preventDefault();
      return;
    }

    // Substitui a senha pela hash antes de enviar
    const senhaHash = await sha256(senhaInput.value);
    senhaInput.value = senhaHash;
  });
});
