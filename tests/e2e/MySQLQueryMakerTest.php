<?php

use PHPUnit\Framework\TestCase;
use QueryMaker\MySQLQueryMaker;

class MySQLQueryMakerTest extends TestCase
{
    private PDO $dataBaseConnection;
    private MySQLQueryMaker $queryMaker; 

    public function setup(): void
    {
        $this->dataBaseConnection = new PDO('mysql:host=localhost;dbname=test', 'root', '');
        $this->queryMaker = new MySQLQueryMaker('users', $this->dataBaseConnection);
    }

    /** @test */
    public function itShouldInsertDataIntoDataBase(): void
    {
        $result = $this->queryMaker->insert(true, 'fff', 'felizardo', 30);

        $this->assertTrue($result);
    }

    /** @test */
    public function itShouldSelectOneRowBasedOnTheGivenId(): void
    {        
        $row = $this->queryMaker->selectOne(3);

        $this->assertIsArray($row);        
        $this->assertArrayHasKey('id', $row);
    }

    /** @test */
    public function itShouldThrowExceptionIfTheIdDoesNotExist(): void
    {        
        $result = $this->queryMaker->selectOne(9999999999);

        $this->assertFalse($result);
    }

    /** @test */
    public function itShouldSelectDataFromDatabase(): void
    {
        $row = $this->queryMaker->select(1, 5);

        $this->assertIsArray($row);
    }

    /** @test */
    public function itShouldThrowExceptionIfDataLengthDoesNotCorrespondToColumns(): void
    {
        $autoBindParamMethod = self::getMethod($this->queryMaker, 'validateColumnsLength');
            
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("provided data array length does not correspond to columns");

        $autoBindParamMethod->invokeArgs($this->queryMaker,[[1, 2], [1]]);
    }

    public function testDelete(): void
    {
        $this->markTestSkipped('this test can arbitrarly broke the consistance of the data base');
        
        $isDelected = $this->queryMaker->delete(2);

        $this->assertTrue($isDelected);
    }

    private static function getMethod(object $className, string $methodName): ReflectionMethod
    {
        $class = new ReflectionClass($className::class);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}
