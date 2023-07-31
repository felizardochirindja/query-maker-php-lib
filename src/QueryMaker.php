<?php

namespace QueryMaker;

interface QueryMaker
{
    function insert(bool $useId, mixed ...$data): bool;
    function select(int $firstRecordPosition, int $recordsPerPage): array;
    function selectOne(int $id): array | false;
    function update(int $id, mixed ...$data): bool;
    function delete(int $id): bool;
}
