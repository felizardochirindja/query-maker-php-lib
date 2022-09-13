<?php

require '../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use QueryMaker\Core\MySQLQueryMaker;
use QueryMaker\Model\MainModel;

class MySQLQueryMakerTest extends TestCase
{
    public function testInsert()
    {
        $this->markTestSkipped('this test can arbitrarly broke the consistance of the data base');

        // arange

        /**
         * @var MainModel
        */
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();

        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_acl', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnection);
        
        // $this->expectException(InvalidArgumentException::class);

        // act
        $result = $queryMaker->insert(false, 'felizardo', 30);

        // assert
        $this->assertTrue($result);
    }

    public function testSelectOne()
    {
        // arange

        /**
         * @var MainModel
        */
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();
        
        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_acl', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnection);

        // act
        $row = $queryMaker->selectOne(1);

        // // assert
        if (count($row) > 0) {            
            $this->assertIsArray($row);
            $this->assertArrayHasKey('id', $row);
            $this->assertArrayHasKey('username', $row);
            $this->assertArrayHasKey('user_password', $row);
        }

        if (count($row) === 0) {
            $this->assertSame([], $row);
        }
    }

    public function testSelect()
    {
        // arange

        /**
         * @var MainModel
        */
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();
        
        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_acl', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnection);

        // act
        $row = $queryMaker->select(1, 5);

        // assert
        $this->assertIsArray($row);
    }

    public function testUpdate()
    {
        $this->markTestSkipped('this test can arbitrarly broke the consistance of the data base');

        // arrange

        /**
         * @var MainModel
        */
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();

        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_acl', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnection);

        $updated = $queryMaker->update(1, 'Felizardo', 125448);

        $this->assertTrue($updated);
    }

    public function testDelete()
    {
        $this->markTestSkipped('this test can arbitrarly broke the consistance of the data base');

        // arange

        /**
         * @var MainModel
        */
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();
        
        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_query_maker', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnection);

        // act
        $isDelected = $queryMaker->delete(2);

        // assert
        $this->assertTrue($isDelected);
    }

    public function testAutoBindParam()
    {
        // arange
        $this->markTestSkipped();
        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_query_maker', 'root', '');

        /**
         * @var MainModel
        */
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();
        
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnection);
        $autoBindParamMethod = self::getMethod($queryMaker, 'autoBindParam');
            
        // act
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
        
        var_dump($result);

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
