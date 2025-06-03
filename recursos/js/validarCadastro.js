document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('.formCadastro');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    const usuario = document.querySelector('input[name="usuario"]').value.trim();
    const senha = document.querySelector('input[name="senha"]').value;
    const confirmarSenha = document.querySelector('input[name="confirmar_senha"]').value;

    // Campos obrigatórios
    if (!usuario || !senha || !confirmarSenha) {
      alert('Todos os campos são obrigatórios.');
      e.preventDefault();
      return;
    }

    // Validação de nome de usuário
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

    // Validação de senha forte
    const senhaForte = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!senhaForte.test(senha)) {
      alert('A senha deve conter ao menos 8 caracteres, incluindo letras maiúsculas, minúsculas e números.');
      e.preventDefault();
      return;
    }

    if (senha !== confirmarSenha) {
      alert('As senhas não coincidem.');
      e.preventDefault();
      return;
    }
  });
});
