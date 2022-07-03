<?php

require '../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use QueryMaker\Core\MySQLQueryMaker;
use QueryMaker\Model\MainModel;

class MySQLQueryMakerTest extends TestCase
{
    public function testInsert()
    {
        // $this->markTestSkipped('evitando insercao de dados no banco de dados');

        // arange

        /**
         * @var MainModel
        */
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();

        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_query_maker', 'root', '');
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
        
        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_query_maker', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnection);

        // act
        $row = $queryMaker->selectOne(10);

        // // assert
        if (count($row) > 0) {
            foreach ($row as $key => $value) {
                $keys[] = $key;
            }
            
            $this->assertIsArray($row);
            $this->assertSame('id', $keys[0]);
            $this->assertSame('name', $keys[1]);
            $this->assertSame('password', $keys[2]);
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
        
        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_query_maker', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnection);

        // act
        $row = $queryMaker->select(1, 5);

        // assert
        $this->assertIsArray($row);
    }

    public function testUpdate()
    {
        // arrange

        /**
         * @var MainModel
        */
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();

        $dataBaseConnection = new PDO('mysql:host=localhost;dbname=test_query_maker', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnection);

        $updated = $queryMaker->update(1, 'Felizardo', 125448);

        $this->assertTrue($updated);
    }

    public function testDelete()
    {
        $this->markTestSkipped('metodo pulado para que nao sejam eliminados dados no banco');

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
        $dbConnector = new MySQLConnector('localhost', 'test', 'root', '');
        $dbConnection = $dbConnector->getConnection();

        /**
         * @var MainModel
        */
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();
        
        $queryMaker = new MySQLQueryMaker($model, $dbConnector);
        $autoBindParamMethod = self::getMethod($queryMaker, 'autoBindParam');
            
        // act
        $query = 'SELECT * FROM users WHERE id = :id';
        $statment = $dbConnection->prepare($query);

        $result = $autoBindParamMethod->invokeArgs(
            $queryMaker, 
            [
                $statment,
                ['id'], [1]
            ]
        );

        $result->execute();
        $result = $result->fetch(PDO::FETCH_ASSOC);

        // assert
        $this->assertIsArray($result);
        $this->assertEquals(
            ['id' => '1', 'name' => 'felizardo', 'password' => '30'],
            $result
        );

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
