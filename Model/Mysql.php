<?php

class Mysql
{
    protected $conn;
    protected $db = 'typeracer';

    public function __construct()
    {
        $this->conn = mysqli_connect('localhost', 'root', '', $this->db);

        if (!$this->conn) {
            die("DB connection error: " . mysqli_connect_error());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function closeConnection()
    {
        if ($this->conn) {
            mysqli_close($this->conn);
        }
    }
}

?>