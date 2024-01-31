<?php

namespace Api;

header('Content-Type: application/json');
include_once('config.php');

class Database
{
    public $conn;

    public function __construct()
    {
        $this->conn = new \mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}
