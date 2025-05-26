-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS clinica_medica;
USE clinica_medica;

-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
  email VARCHAR(255) NOT NULL UNIQUE,
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


