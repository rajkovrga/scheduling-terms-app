<?php

namespace SchedulingTerms\App\Core\Data;

use PDO;
use PDOException;
use PDOStatement;

class DatabaseConnection {
    private string $host;
    private string $port;
    private string $username;
    private string $password;
    private string $dbname;
    private string $charset;
    private ?PDO $pdo = null;

    public function __construct($host, $port, $username, $password, $dbname, $charset) {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->charset = $charset;

        $this->connect();
    }

    private function connect() {
        $dsn = "pgsql:host=$this->host:$this->port;dbname=$this->dbname;options='-c client_encoding=$this->charset'";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function executeQuery($query, $params = []): PDOStatement|false {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        } finally {
            $this->close();
        }
    }


    public function close(): void {
        $this->pdo = null;
    }
}