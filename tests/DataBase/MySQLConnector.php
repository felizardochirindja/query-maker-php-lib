<?php

require __DIR__ . '/../../src/Contracts/DataBaseConnector.php';

use QueryMaker\Contracts\DataBaseConnector;

class MySQLConnector implements DataBaseConnector
{
    private PDO $connection;

    /**
     * @throws PDOException
    */
    public function __construct(
        private string $host,
        private string $dataBaseName,
        private string $user,
        private string $password
    ) {
        try {
            $this->connection = new PDO(
                'mysql:host=' . $this->host . 
                ';dbname='    . $this->dataBaseName,
                $this->user,    $this->password
            );
        } catch (PDOException $pdoException) {
            echo 'Conection failed: ' . $pdoException->getMessage();
        }
    }

    public function getConnection() : PDO
    {
        return $this->connection;
    }

    public function closeConnection() : void
    {
        $this->connection = null;
    }
}
