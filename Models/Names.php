<?php

include_once 'config/Database.php';
include_once 'db_abstract/database.php';


class Names extends Database_Abs
{
    protected $conn;
    protected $table = 'names';
    protected $primaryKey = 'id';

    protected $columns  =[
        'id',
        'name',
    ];

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }



}