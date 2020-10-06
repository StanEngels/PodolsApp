<?php

declare(strict_types=1);

namespace DAL;

use interfaces\Idb;
use PDO;
use PDOException;
use PDOStatement;


class db implements Idb
{
    private ?PDOStatement $stmt = null;
    private ?PDO $dbHandler = null;
    private array $error = [];

    private string $dbHost;
    private string $dbName;
    private string $dbUser;
    private string $dbPass;

    public function __construct(string $dbHost, string $dbName, string $dbUser, string $dbPass)
    {
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
    }

    private function connect(): array
    {
        $dbSource = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->dbHandler = new PDO($dbSource, $this->dbUser, $this->dbPass, $options);
        } catch (\PDOException $e) {
            $this->error["dbError"] = "Server is not responding please come back later.";
        }
        return $this->error;
    }

    private function resultSet(): array
    {
        $this->stmt->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function actionQuery(string $sql): void
    {
        $this->connect();
        $this->stmt = $this->dbHandler->prepare($sql);
        $this->stmt->execute();
    }

    public function selectQuery(string $sql): array
    {
        $this->connect();
        $this->stmt = $this->dbHandler->prepare($sql);
        return $this->resultSet();
    }
}
