<?php 
  class Database {
    // DB Params
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;
    private $port;

    public function __construct(){
      $this->username = getenv('USERNAME');
      $this->password = getenv('PASSWORD');
      $this->db_name = getenv('DBNAME');
      $this->host = getenv('HOST');
      $this->port = getenv('DBPORT');
    }

    // DB Connect
    public function connect() {
      $this->conn = null;
      $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};";
      try { 
        $this->conn = new PDO($dsn, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
  }