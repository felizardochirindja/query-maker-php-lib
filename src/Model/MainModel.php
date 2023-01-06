<?php

namespace QueryMaker\Model;

abstract class MainModel
{
    protected int $id;

    public function __construct(
        public string $tableName
    ) {}
}
