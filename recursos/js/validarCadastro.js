document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('.formCadastro');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    const usuario = document.querySelector('input[name="usuario"]').value.trim();
    const senha = document.querySelector('input[name="senha"]').value;
    const confirmarSenha = document.querySelector('input[name="confirmar_senha"]').value;

    if (!usuario || !senha || !confirmarSenha) {
      alert('Todos os campos são obrigatórios.');
      e.preventDefault();
      return;
    }

    if (usuario.length < 3) {
      alert('O nome de usuário deve ter pelo menos 3 caracteres.');
      e.preventDefault();
      return;
    }

    i

    if (senha !== confirmarSenha) {
      alert('As senhas não coincidem.');
      e.preventDefault();
      return;
    }
  });
});
