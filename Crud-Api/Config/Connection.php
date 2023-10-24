<?php


use \PDO;

class Connection
{
    private $host = 'localhost';
    private $user = 'username';
    private $pass = 'password';
    private $dbname = 'tasks';

    public function connect()
    {
        try{
        $conn_str = "pgsql:host=$this->host;dbname=$this->dbname";
        $conn = new PDO($conn_str, $this->user, $this->pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }catch (PDOException $e) {
            echo "Erro na conexão com o banco de dados: " . $e->getMessage();
            die();
        }
}

}
?>
