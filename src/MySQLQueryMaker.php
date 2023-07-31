<?php

namespace QueryMaker;

use function count;

use Exception;
use PDO;
use PDOStatement;
use QueryMaker\QueryMaker;

final class MySQLQueryMaker implements QueryMaker
{
    function __construct(
        private string $tableName,
        private PDO $dataBaseConnection
    ) {}

    function insert(bool $useId = false, mixed ...$data): bool
    {   
        $columns = $this->getColumns($useId);
        $this->validateColumnsLength($columns, $data);

        $query =
            'INSERT INTO ' . $this->tableName .
            ' (' . $this->appendColumns($columns) . ')
            VALUES (' . $this->appendBindParams($columns) . ');'
        ;

        $statment = $this->dataBaseConnection->prepare($query);

        if ($useId) {
            // colocar o valor default nos dados
        }

        $this->autoBindParam($statment, $columns, $data)->execute();

        return true;
    }

    function getColumns(bool $useId): array
    {
        $query = 'SHOW COLUMNS FROM ' . $this->tableName;

        $statment = $this->dataBaseConnection->prepare($query);
        $statment->execute();

        foreach ($statment->fetchAll(PDO::FETCH_ASSOC) as $value) {
            $columns[] = $value['Field'];
        }

        if (!$useId) {
            array_shift($columns);
        }

        return $columns;
    }

    private function appendColumns(array $columnNames): string
    {
        $columns = '';

        for ($i = 0; $i < count($columnNames) - 1; $i++) {
            $columns .= $columnNames[$i] . ', ';
        }

        $columns .= $columnNames[count($columnNames) - 1];

        return $columns;
    }

    private function appendBindParams(array $columnNames): string
    {
        $columns = '';

        for ($i = 0; $i < count($columnNames) - 1; $i++) { 
            $columns .= ':' . $columnNames[$i] . ', ';
        }

        $columns .= ':' . $columnNames[count($columnNames) - 1];

        return $columns;
    }
    
    private function autoBindParam(
        PDOStatement $statment,
        array $columns,
        array $data    
    ): PDOStatement
    {
        for ($i = 0; $i < count($columns); $i++) {
            $statment->bindParam(":" . $columns[$i], $data[$i]);
        }

        return $statment;
    }
  
    function select(int $firstRecordPosition, int $recordsPerPage): array
    {
        $query =
            'SELECT * FROM ' . $this->tableName .
            " LIMIT $firstRecordPosition, $recordsPerPage;";

        $statment = $this->dataBaseConnection->prepare($query);
        $statment->execute();

        return $statment->fetchAll(PDO::FETCH_ASSOC);
    } 

    function selectOne(int $id): array | false
    {
        $query = 'SELECT * FROM ' . $this->tableName . ' WHERE id = :id;';

        $statment = $this->dataBaseConnection->prepare($query);
        $statment->bindParam(':id', $id);
        $statment->execute();

        return $statment->fetch(PDO::FETCH_ASSOC);
    }

    function update(int $id, mixed ...$data): bool
    {
        $columns = $this->getColumns(false);
        $this->validateColumnsLength($columns, $data);

        

        return true;
    }

    private function validateColumnsLength(array $columns, array $data): void
    {
        if (count($columns) !== count($data)) {
            throw new Exception("provided data array length does not correspond to columns");
        }
    }

    function delete(int $id): bool
    {
        $query = 'DELETE FROM ' . $this->tableName . ' WHERE id = :id;';

        $statment = $this->dataBaseConnection->prepare($query);
        $statment->bindParam(':id', $id);
        return $statment->execute();
    }
}
