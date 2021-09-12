<?php

namespace QueryMaker\Contracts;

interface QueryMaker
{
    function insert(bool $useId,mixed ...$data) : bool;
    function select(int $firstRecordPosition, int $recordsPerPage) : array;
    function selectOne(int $id) : array;
    // function update() : bool;
    function delete(int $id) : bool;
}
