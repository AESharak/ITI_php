<?php
class Database {
    private $connection;
    
    public function connect($host, $username, $password, $database) {
        try {
            $dsn = "mysql:host=$host;dbname=$database";
            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->connection;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public function insert($table, $data) {
        $columns = array_keys($data);
        $columnsString = implode(", ", $columns);
        
        $placeholders = array_fill(0, count($columns), "?");
        $placeholdersString = implode(", ", $placeholders);
        
        $sql = "INSERT INTO $table ($columnsString) VALUES ($placeholdersString)";
        
        try {
            $stmt = $this->connection->prepare($sql);
            
            $i = 1;
            foreach ($data as $value) {
                $stmt->bindValue($i++, $value);
            }
            
            $result = $stmt->execute();
            return $result ? $this->connection->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("Database insert error: " . $e->getMessage());
            return false;
        }
    }
    
    public function select($table, $columns = "*", $whereClause = null, $params = []) {
        if (is_array($columns)) {
            $columns = implode(", ", $columns);
        }
        
        $sql = "SELECT $columns FROM $table";
        
        if ($whereClause !== null) {
            $sql .= " WHERE $whereClause";
        }
        
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!empty($params)) {
                $i = 1;
                foreach ($params as $param) {
                    $stmt->bindValue($i++, $param);
                }
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database select error: " . $e->getMessage());
            return false;
        }
    }
    
    // this for testing
    public function update($table, $id, $data) {
        $setParts = [];
        foreach ($data as $column => $value) {
            $setParts[] = "$column = ?";
        }
        $setString = implode(", ", $setParts);
        
        $sql = "UPDATE $table SET $setString WHERE id = ?";
        
        try {
            $stmt = $this->connection->prepare($sql);
            
            $i = 1;
            foreach ($data as $value) {
                $stmt->bindValue($i++, $value);
            }
            // Bind the ID parameter last
            $stmt->bindValue($i, $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database update error: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($table, $id) {
        $sql = "DELETE FROM $table WHERE id = ?";
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(1, $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database delete error: " . $e->getMessage());
            return false;
        }
    }
}
?>