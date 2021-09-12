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
        // arange
        $model = $this->getMockBuilder(MainModel::class)
            ->setConstructorArgs(['users'])
            ->getMock();

        $dataBaseConnector = new MySQLConnector('localhost', 'test', 'root', '');
        $queryMaker = new MySQLQueryMaker($model, $dataBaseConnector);
        
        $this->expectException(InvalidArgumentException::class);

        // act
        $queryMaker->insert(true, 'felizardo', 15268);
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
        $row = $queryMaker->selectOne(30);

        // // assert
        $this->assertIsArray($row);
        $this->assertSame([], $row);
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
}
