<?php

namespace QueryMaker\Core;

use function count;

use InvalidArgumentException;
use PDO;
use PDOStatement;
use QueryMaker\Contracts\DataBaseConnector;
use QueryMaker\Contracts\QueryMaker;
use QueryMaker\Model\MainModel;

final class MySQLQueryMaker implements QueryMaker
{
    private PDO $dataBaseConnection;

    public function __construct(
        private MainModel $model,
        DataBaseConnector $dataBaseConnector
    ) {
        $this->dataBaseConnection = $dataBaseConnector->getConnection();
    }

    public function insert(mixed $useId, mixed ...$data) : bool
    {
        if (!is_bool($useId)) {
            throw new InvalidArgumentException(
                "userId argument missing: you must pass true or false"
            );
        }
        
        $columns = $this->getColumnNames($useId);

        $query = 'INSERT INTO ' . $this->model->tableName . ' ' .
            $this->appendColumnNames($columns) .
            ' VALUES ' . $this->appendBindParams($columns) . ';';

        $statment = $this->dataBaseConnection->prepare($query);

        $this->automateBindParam($statment, $columns, $data)->execute();

        return true;
    }

    private function getColumnNames(bool $useId) : array
    {
        $query = 'SHOW COLUMNS FROM ' . $this->model->tableName;

        $statment = $this->dataBaseConnection->prepare($query);
        $statment->execute();

        $columns = [];

        foreach ($statment->fetchAll(PDO::FETCH_ASSOC) as $value) {
            $columns[] = $value['Field'];
        }

        if (!$useId) {
            array_shift($columns);
        }

        return $columns;
    }

    private function appendColumnNames(array $columnNames) : string
    {
        $columns = '(';

        for ($i = 0; $i < count($columnNames) - 1; $i++) { 
            $columns .= $columnNames[$i] . ', ';
        }

        $columns .= $columnNames[count($columnNames) - 1] . ')';

        return $columns;
    }

    private function appendBindParams(array $columnNames) : string
    {
        $columns = '(';

        for ($i = 0; $i < count($columnNames) - 1; $i++) { 
            $columns .= ':' . $columnNames[$i] . ', ';
        }

        $columns .= ':' . $columnNames[count($columnNames) - 1] . ')';

        return $columns;
    }
    
    private function automateBindParam(
        PDOStatement $statment,
        array $columns,
        array $data    
    ) : PDOStatement
    {
        for ($i = 0; $i < count($columns); $i++) {
            $statment->bindParam(":" . $columns[$i], $data[$i]);
        }

        return $statment;
    }
  
    public function select(int $firstRecordPosition, int $recordsPerPage) : array
    {
        $query = 'SELECT * FROM ' . $this->model->tableName .
            " LIMIT $firstRecordPosition, $recordsPerPage;";

        $statment = $this->dataBaseConnection->prepare($query);
        $statment->execute();

        if ($statment->rowCount() > 0) {
            return $statment->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function selectOne(int $id) : array
    {
        $query = 'SELECT * FROM ' . $this->model->tableName . ' WHERE id = :id;';

        $statment = $this->dataBaseConnection->prepare($query);
        $statment->bindParam(':id', $id);
        $statment->execute();

        if ($statment->rowCount() === 1) {
            return $statment->fetch(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function update(int $id, array $data) : bool
    {
        return true;
    }

    public function delete(int $id) : bool
    {
        $query = 'DELETE FROM ' . $this->model->tableName . ' WHERE id = :id;';

        $statment = $this->dataBaseConnection->prepare($query);
        $statment->bindParam(':id', $id);
        $statment->execute();

        return true;
    }
}
