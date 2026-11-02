<?php
// Database configuration
$host = "localhost";
$dbname = "project_db";
$username = "root";   // change if needed
$password = "";       // change if needed

try {
    // Simple PDO connection with basic options
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Database helper class for common operations
class Database {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Insert a new record
    public function insert($table, $data) {
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));//return name clum
            
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw $e;
        }
    }
    
    // Update a record
    public function update($table, $data, $where, $whereParams = []) {
        try {
            $setClause = [];
            foreach (array_keys($data) as $column) {
                $setClause[] = "$column = :$column";
            }
            $setClause = implode(', ', $setClause);
            
            $sql = "UPDATE $table SET $setClause WHERE $where";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array_merge($data, $whereParams));
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw $e;
        }
    }
    
    // Delete a record
    public function delete($table, $where, $params = []) {
        try {
            $sql = "DELETE FROM $table WHERE $where";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw $e;
        }
    }
    
    // Select records
    public function select($table, $columns = '*', $where = '', $params = [], $orderBy = '', $limit = '') {
        try {
            $sql = "SELECT $columns FROM $table";
            if ($where) {
                $sql .= " WHERE $where";
            }
            if ($orderBy) {
                $sql .= " ORDER BY $orderBy";
            }
            if ($limit) {
                $sql .= " LIMIT $limit";
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw $e;
        }
    }
    
    // Select a single record
    public function selectOne($table, $columns = '*', $where = '', $params = []) {
        try {
            $sql = "SELECT $columns FROM $table";
            if ($where) {
                $sql .= " WHERE $where";
            }
            $sql .= " LIMIT 1";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw $e;
        }
    }
    
    // Count records
    public function count($table, $where = '', $params = []) {
        try {
            $sql = "SELECT COUNT(*) as count FROM $table";
            if ($where) {
                $sql .= " WHERE $where";
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetch()['count'];
        } catch (PDOException $e) {
            throw $e;
        }
    }
    
    // Execute custom query
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}

// Create database instance
$db = new Database($pdo);
?>
