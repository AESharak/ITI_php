<?php
class Database {
    private $connection;
    
    /**
     * Establishes a connection to the database
     * 
     * @param string $host Database host
     * @param string $username Database username
     * @param string $password Database password
     * @param string $database Database name
     * @return mysqli|false Connection object or false on failure
     */
    public function connect($host, $username, $password, $database) {
        $this->connection = new mysqli($host, $username, $password, $database);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        return $this->connection;
    }
    
    /**
     * Inserts data into a table
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value pairs
     * @return int|false ID of inserted record or false on failure
     */
    public function insert($table, $data) {
        $columns = array_keys($data);
        $columnsString = implode(", ", $columns);
        
        $placeholders = array_fill(0, count($columns), "?");
        $placeholdersString = implode(", ", $placeholders);
        
        $sql = "INSERT INTO $table ($columnsString) VALUES ($placeholdersString)";
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        $types = "";
        $values = [];
        foreach ($data as $value) {
            if (is_int($value)) {
                $types .= "i";
            } elseif (is_float($value)) {
                $types .= "d";
            } else {
                $types .= "s";
            }
            $values[] = $value;
        }
        
        $stmt->bind_param($types, ...$values);
        $result = $stmt->execute();
        $insertId = $this->connection->insert_id;
        $stmt->close();
        
        return $result ? $insertId : false;
    }
    
    /**
     * Retrieves data from a table using prepared statements
     * 
     * @param string $table Table name
     * @param string|array $columns Columns to select
     * @param string|null $whereClause WHERE clause with placeholders
     * @param array $params Parameters for the WHERE clause
     * @return array|false Array of records or false on failure
     */
    public function select($table, $columns = "*", $whereClause = null, $params = []) {
        if (is_array($columns)) {
            $columns = implode(", ", $columns);
        }
        
        $sql = "SELECT $columns FROM $table";
        
        if ($whereClause !== null) {
            $sql .= " WHERE $whereClause";
        }
        
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        if (!empty($params)) {
            $types = "";
            $bindParams = [];
            
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= "i";
                } elseif (is_float($param)) {
                    $types .= "d";
                } else {
                    $types .= "s";
                }
                $bindParams[] = $param;
            }
            
            // Create array with references as required by bind_param
            $bindParamsRef = [];
            $bindParamsRef[] = &$types;
            foreach ($bindParams as $key => $value) {
                $bindParamsRef[] = &$bindParams[$key];
            }
            
            call_user_func_array([$stmt, 'bind_param'], $bindParamsRef);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (!$result) {
            $stmt->close();
            return false;
        }
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        $stmt->close();
        return $data;
    }
    
    /**
     * Updates a record in the table
     * 
     * @param string $table Table name
     * @param int $id ID of the record to update
     * @param array $data Associative array of column => value pairs
     * @return bool True on success, false on failure
     */
    public function update($table, $id, $data) {
        $setParts = [];
        foreach ($data as $column => $value) {
            $setParts[] = "$column = ?";
        }
        $setString = implode(", ", $setParts);
        
        $sql = "UPDATE $table SET $setString WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        $types = "";
        $values = [];
        foreach ($data as $value) {
            if (is_int($value)) {
                $types .= "i";
            } elseif (is_float($value)) {
                $types .= "d";
            } else {
                $types .= "s";
            }
            $values[] = $value;
        }
        // Add type for ID
        $types .= "i";
        $values[] = $id;
        
        $stmt->bind_param($types, ...$values);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    /**
     * Deletes a record from the table
     * 
     * @param string $table Table name
     * @param int $id ID of the record to delete
     * @return bool True on success, false on failure
     */
    public function delete($table, $id) {
        $sql = "DELETE FROM $table WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
}
?>