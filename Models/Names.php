<?php

include_once '../../config/Database.php';


class Names
{
    private $conn;
    private $table = 'names';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }



}