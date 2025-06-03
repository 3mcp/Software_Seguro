async function sha256(texto) {
  const encoder = new TextEncoder();
  const data = encoder.encode(texto);
  const hashBuffer = await crypto.subtle.digest('SHA-256', data);
  return Array.from(new Uint8Array(hashBuffer))
    .map(b => b.toString(16).padStart(2, '0'))
    .join('');
}

document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('.formCadastro');
  if (!form) return;

  form.addEventListener('submit', async function (e) {
    const usuario = document.querySelector('input[name="usuario"]').value.trim();
    const senhaInput = document.querySelector('input[name="senha"]');
    const confirmarSenha = document.querySelector('input[name="confirmar_senha"]').value;

    const senha = senhaInput.value;

    if (!usuario || !senha || !confirmarSenha) {
      alert('Todos os campos são obrigatórios.');
      e.preventDefault();
      return;
    }

    if (usuario.length < 3 || usuario.length > 30) {
      alert('O nome de usuário deve ter entre 3 e 30 caracteres.');
      e.preventDefault();
      return;
    }

    const xssPattern = /[<>"'`]/;
    if (xssPattern.test(usuario)) {
      alert('O nome de usuário contém caracteres inválidos.');
      e.preventDefault();
      return;
    }

    const senhaForte = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{12,}$/;
    if (!senhaForte.test(senha)) {
      alert('A senha deve ter ao menos 12 caracteres, com letras maiúsculas, minúsculas e números.');
      e.preventDefault();
      return;
    }

    if (senha !== confirmarSenha) {
      alert('As senhas não coincidem.');
      e.preventDefault();
      return;
    }

    // Substitui a senha pela hash SHA-256 antes de enviar
    const senhaHash = await sha256(senha);
    senhaInput.value = senhaHash;
    document.querySelector('input[name="confirmar_senha"]').value = senhaHash;
  });
});
