<?php
namespace classes;

use PDO;
use PDOException;
use Exception;

class DatabaseConfig
{
    public static $host = 'localhost';
    public static $dbName = 'ozkanmovie';
    public static $username = 'root';
    public static $password = '';
    public static $charset = 'utf8mb4';
    public static $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
}

class DatabaseConnection
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . DatabaseConfig::$host . ";dbname=" . DatabaseConfig::$dbName . ";charset=" . DatabaseConfig::$charset;
            self::$instance = new PDO($dsn, DatabaseConfig::$username, DatabaseConfig::$password, DatabaseConfig::$options);
        }
        return self::$instance;
    }
}

class pdoHelper
{
    protected $db;

    public function __construct()
    {
        try {
            $this->db = DatabaseConnection::getInstance();
            $this->createLogTable();
        } catch (PDOException $e) {
            throw new Exception("Bağlantı hatası: " . $e->getMessage());
        }
    }

    private function execute($query, $params = [])
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    public function select($query, $params = [], $log = true)
    {
        $status = true;
        $error = null;
        $data = null;
        $rc = 0;
        try {
            $stmt = $this->execute($query, $params);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $rc = $stmt->rowCount();
            if (empty($data)) {
                //  Boş Veri 
                $status = false;
            }
        } catch (PDOException $e) {
            $error = "Sorgu Hatası: " . $e->getMessage();
            $status = false; // Hata
        }
        if ($log) {
            $this->log('select', $query, $params, $error);
        }
        return ['status' => $status, 'data' => $data, 'rc' => $rc, 'error' => $error];
    }


    public function insert($query, $params = [], $log = true)
    {
        $status = false;
        $id = false;
        $error = null;
        try {
            $stmt = $this->execute($query, $params);
            $id = $this->db->lastInsertId();
            $status = true;
        } catch (PDOException $e) {
            $error =  $e->getMessage();
        }

        if ($log) {
            $this->log('insert', $query, $params, $error);
        }

        return ['status' => $status, 'id' => $id, 'error' => $error];
    }

    public function update($query, $params = [], $log = true)
    {
        $status = false;
        $error = null;
        $rc = false;
        try {
            $stmt = $this->execute($query, $params);
            $rowCount = $stmt->rowCount();
            if ($rowCount > 0) {
                $status = true;
                $rc = $rowCount;
            } else {
                $error = "Güncelleme başarısız: Kayıt bulunamadı veya aynı değere güncelleme denendi.";
            }
        } catch (PDOException $e) {
            $error = "Güncelleme hatası: " . $e->getMessage();;
        }
        if ($log) {
            $this->log("update", $query, $params, $error);
        }
        return ['status' => $status, "rc" => $rc, "error" => $error];
    }

    public function delete($query, $params = [], $log = true)
    {
        $status = false;
        $error = false;
        $rc = false;
        try {
            $stmt = $this->execute($query, $params);
            $rowCount = $stmt->rowCount();
            if ($rowCount > 0) {
                $status = true;
                $rc = $rowCount;
            } else {
                $error = 'Silme başarısız: Kayıt bulunamadı.';
            }
        } catch (PDOException $e) {
            $error = "Silme hatası: " . $e->getMessage();
        }
        if ($log) {
            $this->log('delete', $query, $params, $error);
        }
        return ['status' => $status, 'rc' => $rc, 'error' => $error];
    }

    private function createLogTable()
    {
        $logTableStructure = "
        CREATE TABLE IF NOT EXISTS pdoLog (
            logId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            logIp VARCHAR(45),
            logTableName VARCHAR(50),
            logAction VARCHAR(10),
            logParams TEXT,
            logError TEXT COMMENT 'null: success', 
            logCreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";
        $this->execute($logTableStructure);
    }

    private function log($action, $query, $params, $error)
    {
        $tableName = $this->getTableName($query);
        $logParams = json_encode(['query' => $query, 'params' => $params]);

        $logQuery = "INSERT INTO pdoLog (logIp, logTableName, logAction, logParams, logError) VALUES (:ip, :tableName, :action, :params, :error)";
        $logParams = [
            ':ip' => $_SERVER['REMOTE_ADDR'],
            ':tableName' => $tableName,
            ':action' => $action,
            ':params' => $logParams,
            ':error' => $error
        ];
        $this->execute($logQuery, $logParams);
    }

    private function getTableName($query)
    {
        $patterns = [
            'update' => '/\bUPDATE\s+\`?(\w+)\`?/i',
            'insert' => '/\bINTO\s+\`?(\w+)\`?/i',
            'select' => '/\bFROM\s+\`?(\w+)\`?/i',
            'delete' => '/\bDELETE\s+FROM\s+\`?(\w+)\`?/i'
        ];
        
        foreach ($patterns as $type => $pattern) {
            if (stripos($query, $type) === 0) {
                preg_match($pattern, $query, $matches);
                return isset($matches[1]) ? $matches[1] : false;
            }
        }
    
        return false;
    }
}