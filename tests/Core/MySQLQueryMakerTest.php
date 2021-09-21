<?php

require '../../vendor/autoload.php';
require '../DataBase/MySQLConnector.php';

use PHPUnit\Framework\TestCase;
use QueryMaker\Core\MySQLQueryMaker;
use QueryMaker\Model\MainModel;

class MySQLQueryMakerTest extends TestCase
{
    public function testInsert()
    {
        $this->markTestSkipped('evitando insercao de dados no banco de dados');

        // arange
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();

        $dataBaseConnector = new MySQLConnector('localhost', 'test', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnector);
        
        $this->expectException(InvalidArgumentException::class);

        // act
        $queryMaker->insert(false, 'felizardo', 30);
    }

    public function testSelectOne()
    {
        // arange
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();
        
        $dataBaseConnector = new MySQLConnector('localhost', 'test', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnector);

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
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();
        
        $dataBaseConnector = new MySQLConnector('localhost', 'test', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnector);

        // act
        $row = $queryMaker->select(1, 5);

        // assert
        $this->assertIsArray($row);
    }

    public function testUpdate()
    {
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();

        $dataBaseConnector = new MySQLConnector('localhost', 'test', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnector);

        $updated = $queryMaker->update(1, 'Felizardo', 125448);

        $this->assertTrue($updated);
    }

    public function testDelete()
    {
        $this->markTestSkipped('metodo pulado para que nao sejam eliminados dados no banco');

        // arange
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();
        
        $dataBaseConnector = new MySQLConnector('localhost', 'test', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnector);

        // act
        $isDelected = $queryMaker->delete(2);

        // assert
        $this->assertTrue($isDelected);
    }

    public function testAutomateBindParam()
    {
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
