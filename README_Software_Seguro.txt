
Software Seguro — Sistema de Clínica Médica

Sistema de cadastro e gerenciamento de pacientes, médicos, especialidades e consultas.

✅ Visão Geral

- Linguagem: PHP 8.x
- Arquitetura: MVC puro
- Banco de Dados: MySQL / MariaDB
- Segurança:
  - CSRF Tokens validados
  - Senhas armazenadas com SHA-256
- Autoload: PSR-4 via Composer
- Sem uso de frameworks externos (apenas PHP puro + composer)
- Sistema REST centralizado no index.php

✅ Pré-requisitos para rodar o projeto

- PHP 8.x (já incluído no XAMPP)
- MySQL ou MariaDB (já incluído no XAMPP)
- Composer instalado (https://getcomposer.org/)
- XAMPP (recomendado para Windows)

✅ Instalação passo a passo (Windows com XAMPP)

1️⃣ Copiar o projeto para o servidor local

- Copie a pasta completa do projeto Software_Seguro para dentro de:

C:\xampp\htdocs\

O caminho final ficará:

C:\xampp\htdocs\Software_Seguro\

2️⃣ Importar o banco de dados

- No XAMPP, abra o phpMyAdmin:  
  http://localhost/phpmyadmin

- Crie um banco de dados vazio chamado:

clinica_medica

- Importe o arquivo SQL que está no projeto:

database/clinica_medica.sql

Isso criará todas as tabelas necessárias.

3️⃣ Configurar o arquivo .env

O projeto utiliza variáveis de ambiente via phpdotenv.

- Na raiz do projeto existe o arquivo:

.env.example

- Faça uma cópia e renomeie como:

.env

- Abra o .env e preencha as informações conforme o seu banco local:

DB_HOST=127.0.0.1
DB_NAME=clinica_medica
DB_USER=root
DB_PASS=

Obs: se o seu MySQL tiver senha, preencha em DB_PASS.

4️⃣ Rodar o Composer

Abra o terminal (ou prompt de comando) e vá até a pasta do projeto:

cd C:\xampp\htdocs\Software_Seguro

Em seguida execute:

composer install

ou:

composer dump-autoload

Assim o autoload PSR-4 do projeto será configurado corretamente.

5️⃣ Iniciar o XAMPP

- Abra o painel de controle do XAMPP.
- Inicie os serviços:

  - Apache ✅
  - MySQL ✅

6️⃣ Acessar o sistema

Abra o navegador e acesse:

http://localhost/Software_Seguro/application/index.php?pagina=login

Pronto. O sistema já estará rodando.

✅ Primeira utilização

- Na tela de login há opção de cadastro inicial.
- Após cadastrar o primeiro usuário, já poderá logar normalmente.

✅ Estrutura de Diretórios

Software_Seguro/
│
├── application/
│   ├── Controllers/
│   ├── models/
│   ├── View/
│   └── index.php  <-- arquivo principal de roteamento
│
├── config/
│   └── config.php
│
├── database/
│   └── clinica_medica.sql
│
├── recursos/
│   ├── css/
│   ├── js/
│   └── images/
│
├── utils/
│   ├── CSRFToken.php
│   ├── CSRFValidador.php
│   ├── Logger.php
│   ├── Sessao.php
│   └── VerificaSessao.php
│
├── vendor/
│   └── (composer autoload)
│
├── .env.example
├── .env  <-- após configurado
├── composer.json
├── composer.lock
└── README.md

✅ Considerações Técnicas

- O sistema utiliza index.php?pagina= para views e index.php?action= para API.
- As requisições de cadastro, edição e exclusão são feitas via fetch() com JSON.
- Todos os POST exigem token CSRF válido.
- Senhas são armazenadas no banco usando SHA-256 para segurança.
- Não há uso de PDO, todo o banco roda com MySQLi procedural.

✅ Projeto estável, pronto para desenvolvimento e utilização.
