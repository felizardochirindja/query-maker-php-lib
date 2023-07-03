<?php

namespace QueryMaker;

abstract class MainModel
{
    protected int $id;

    public function __construct(
        public string $tableName
    ) {}
}
