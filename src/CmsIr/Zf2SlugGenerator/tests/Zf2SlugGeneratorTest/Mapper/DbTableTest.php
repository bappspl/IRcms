<?php
namespace Zf2SlugGeneratorTest\Mapper;

use Zf2SlugGenerator\Mapper\DbTable;
class DbTableTest extends \PHPUnit_Framework_TestCase
{
    protected $resultObj;
    
    /**
     * Setup class
     */
    public function setUp()
    {
        $this->resultObj = $this->getMockBuilder('Zf2SlugGenerator\Mapper\DbTable')
            ->disableOriginalConstructor()
            ->setMethods(null) // NULL allows actual code contained to be ran!
            ->getMock();
    }
    
    public function testSetTableNameProtectedParam()
    {
        $this->resultObj->setTableName('randomTableName');
        
        $this->assertAttributeEquals(
            'randomTableName',
            'tableName',
            $this->resultObj
        );
    }

    public function testSetColNameProtectedParam()
    {
        $this->resultObj->setColName('randomColName');
    
        $this->assertAttributeEquals(
            'randomColName',
            'colName',
            $this->resultObj
        );
    }

    public function testSetExclusionStringProtectedParam()
    {
        $this->resultObj->setExclusionString('randomExclusionString');
    
        $this->assertAttributeEquals(
            'randomExclusionString',
            'exclusionString',
            $this->resultObj
        );
    }
    
    public function testSetTableNameReturnType()
    {
        $this->assertTrue($this->resultObj->setTableName('randomTableName')
            instanceof DbTable);
    }
    
    public function testSetColNameReturnType()
    {
        $this->assertTrue($this->resultObj->setColName('randomColName')
            instanceof DbTable);
    }
    
    public function testSetExclusionStringReturnType()
    {
        $this->assertTrue($this->resultObj->setExclusionString('randomExclusionString')
            instanceof DbTable);
    }
    
    public function testEmptyGetExclusionString()
    {
        $this->assertEquals(
            null,
            $this->resultObj->getExclusionString()
        );
    }
    
    public function testGetColName()
    {
        $this->resultObj->setColName('randomColName');
        $this->assertEquals(
            'randomColName',
            $this->resultObj->getColName()
        );
    }
    
    public function testGetTableName()
    {
        $this->resultObj->setTableName('randomTableName');
        $this->assertEquals(
            'randomTableName',
            $this->resultObj->getTableName()
        );
    }
    
    public function testGetExclusionString()
    {
        $this->resultObj->setExclusionString('randomExclusionString');
        $this->assertEquals(
            'randomExclusionString',
            $this->resultObj->getExclusionString()
        );
    }
    
    public function testGetTableNameFailsOnEmptyTableName()
    {
        $this->setExpectedException(
            'Zf2SlugGenerator\Mapper\Exception\SlugDbException', 'Missing DBTable validation data'
        );

        $this->resultObj->getTableName();
    }

    public function testGetColNameFailsOnEmptyColName()
    {
        $this->setExpectedException(
            'Zf2SlugGenerator\Mapper\Exception\SlugDbException', 'Missing DBTable validation data'
        );

        $this->resultObj->getColName();
    }

    public function testGetDbAdapterFailsOnEmptyDbAdapter()
    {
        $this->setExpectedException(
            'Zf2SlugGenerator\Mapper\Exception\SlugDbException', 'No db adapter present'
        );

        $this->resultObj->getDbAdapter();
    }

    public function testGetDbAdapter()
    {
        $mock = $this->getMockBuilder('\Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultObj->setDbAdapter($mock);
        $this->assertInstanceOf('\Zend\Db\Adapter\Adapter', $this->resultObj->getDbAdapter());
    }
}
