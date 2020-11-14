<?php

abstract class Database_Abs {

    protected $conn;
    protected $table;
    protected $primaryKey;

    protected $columns =[];

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll()
    {
        // Create query
        $query = 'SELECT * FROM ' . $this->table;

        // Prepare statement
        $result = $this->conn->prepare($query);

        // Execute query
        $result->execute();

        // Get row count
        $num = $result->rowCount();

        // Check if any posts
        if ($num > 0) {
            // Post array
            $data = [];

            while ($rows = $result->fetch(PDO::FETCH_ASSOC)) {

                $row = [];
                foreach ($this->columns as $column){
                    $row[$column] = $rows[$column];
                }
                array_push($data, $row);
            }
            return $data;
        }
        return null;
    }

    public function create(array $data)
    {
        $fields = "";
        foreach ($data as $key => $val){
            if (in_array($key, $this->columns))
            {
                $fields .= " {$key} = :{$key},";
            }
        }
        $query = substr("INSERT INTO " . $this->table . " SET {$fields}", 0, -1);
        $stmt = $this->conn->prepare($query);
        foreach ($data as $key => $val){
            if (in_array($key, $this->columns))
            {
                $stmt->bindValue(":{$key}", $val, PDO::PARAM_STR);
            }
        }
        return $stmt->execute();
    }

    public function destroy($id)
    {
        /* Another way */
//        $query = 'DELETE FROM ' . $this->table . ' WHERE id = ' . $id;
//        $stmt = $this->conn->query($query);
        try {

            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":{$this->primaryKey}", $id, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();// check affected rows using rowCount
            if ($count > 0) {
                return json_encode(['message' => 'Deleted Successfully']);
            } else {
                header("HTTP/1.0 404 Not Found");
                return json_encode(['message' => 'Not Found']);
            }
        } catch (PDOException $e) {
//            echo $e->getMessage();
            return false;
        }
    }


}