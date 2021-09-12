<?php

namespace QueryMaker\Model;

abstract class MainModel
{
    private int $id;

    public function __construct(
        public string $tableName
    ) {}
}
