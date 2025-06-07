-- Script SQL corrigido e adaptado para o projeto da Clínica Médica
DROP DATABASE IF EXISTS clinica_medica;
-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS clinica_medica;
USE clinica_medica;

-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  is_admin BOOLEAN NOT NULL DEFAULT FALSE,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserção de um usuário administrador padrão
-- Usuário: admin | Senha: Admin123456 (com hash gerado via password_hash)
INSERT INTO usuarios (usuario, senha, is_admin) VALUES (
  'admin',
  '$2y$10$D5TY0An6a.qthQTfDSrMXOyACuPtC5bNaonwhB4R5QcBTl6mT0rhm', -- senha: Admin123456
  TRUE
);

-- Tabela de Especialidades
CREATE TABLE IF NOT EXISTS especialidades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL
);

-- Tabela de Pacientes
CREATE TABLE IF NOT EXISTS pacientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  telefone VARCHAR(20) NOT NULL,
  dataNascimento DATE NOT NULL
);

-- Tabela de Médicos
CREATE TABLE IF NOT EXISTS medicos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  crm VARCHAR(50) NOT NULL,
  especialidadeId INT,
  FOREIGN KEY (especialidadeId) REFERENCES especialidades(id)
);

-- Tabela de Consultas
CREATE TABLE IF NOT EXISTS consultas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pacienteId INT,
  medicoId INT,
  especialidadeId INT,
  dataHora DATETIME NOT NULL,
  FOREIGN KEY (pacienteId) REFERENCES pacientes(id) ON DELETE CASCADE,
  FOREIGN KEY (medicoId) REFERENCES medicos(id) ON DELETE CASCADE,
  FOREIGN KEY (especialidadeId) REFERENCES especialidades(id) ON DELETE CASCADE
);