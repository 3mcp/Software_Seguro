<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'clinica_medica';
    private $username = 'root';
    private $password = '12345';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Erro na conexão: " . $this->conn->connect_error);
        }
        return $this->conn;
    }
}
?>