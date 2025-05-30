<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Clínica Médica</title>
  <link rel="stylesheet" href="/Software_Seguro/recursos/css/main.css" />
  </head>
<body>
  <div class="page">
    <form class="formLogin" action="?action=autenticar" method="POST">
      <img src="/Software_Seguro/recursos/images/Screenshot 2025-03-07 170253 .png" alt="Logo Clínica" />
      <h1>Bem-vindo</h1>

      <label for="usuario">Usuário:</label>
      <input type="text" id="usuario" name="usuario" placeholder="Seu usuário" required />

      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="senha" placeholder="Sua senha" required />

      <button type="submit">Entrar</button>

      <p>Não possui login? <a href="?pagina=cadastro">Cadastre-se</a></p>
    </form>
  </div>
  <script src="/Software_Seguro/recursos/js/validarLogin.js"></script>
</body>
</html>