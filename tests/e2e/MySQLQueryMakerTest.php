<?php

use PHPUnit\Framework\TestCase;
use QueryMaker\MySQLQueryMaker;
use QueryMaker\MainModel;

class MySQLQueryMakerTest extends TestCase
{
    public function testInsert()
    {
        // $this->markTestSkipped('this test can arbitrarly broke the consistance of the data base');

        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test', 'root', '');
        $queryMaker = new MySQLQueryMaker('users', $dataBaseConnection);
        
        // $this->expectException(InvalidArgumentException::class);

        $result = $queryMaker->insert(true, 'felizardo', 30, 8);

        $this->assertTrue($result);
    }

    public function testSelectOne()
    {
        // $db = $this->getMockForAbstractClass();
        
        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_acl', 'root', '');
        $queryMaker = new MySQLQueryMaker('users', $dataBaseConnection);

        $row = $queryMaker->selectOne(2);

        $this->assertIsArray($row);        
        $this->assertArrayHasKey('id', $row);
        $this->assertArrayHasKey('username', $row);
        $this->assertArrayHasKey('user_password', $row);
    }

    public function testSelect()
    {
        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_acl', 'root', '');
        $queryMaker = new MySQLQueryMaker('users', $dataBaseConnection);

        $row = $queryMaker->select(1, 5);

        $this->assertIsArray($row);
    }

    public function testUpdate()
    {
        $this->markTestSkipped('this test can arbitrarly broke the consistance of the data base');

        /**
         * @var MainModel
        */
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();

        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_acl', 'root', '');
        $queryMaker = new MySQLQueryMaker('users', $dataBaseConnection);

        $updated = $queryMaker->update(1, 'Felizardo', 125448);

        $this->assertTrue($updated);
    }

    public function testDelete()
    {
        $this->markTestSkipped('this test can arbitrarly broke the consistance of the data base');
        
        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_query_maker', 'root', '');
        $queryMaker = new MySQLQueryMaker('users', $dataBaseConnection);

        $isDelected = $queryMaker->delete(2);

        $this->assertTrue($isDelected);
    }

    public function testAutoBindParam()
    {
        $this->markTestSkipped();
        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_query_maker', 'root', '');

        $queryMaker = new MySQLQueryMaker('users', $dataBaseConnection);
        $autoBindParamMethod = self::getMethod($queryMaker, 'autoBindParam');
            
        $query = 'SELECT * FROM users WHERE id = :id';
        $statment = $dataBaseConnection->prepare($query);

        $result = $autoBindParamMethod->invokeArgs(
            $queryMaker, 
            [
                $statment,
                ['id'], [1]
            ]
        );

        $result->execute();        
    }
    
    protected static function getMethod(
        object $className,
        string $methodName
    ) : ReflectionMethod
    {
        $class = new ReflectionClass($className::class);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}
