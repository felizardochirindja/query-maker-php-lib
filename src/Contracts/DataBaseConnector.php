<?php

namespace QueryMaker\Contracts;

use PDO;

interface DataBaseConnector
{
    public function getConnection() : PDO;
    public function closeConnection() : void;
}
