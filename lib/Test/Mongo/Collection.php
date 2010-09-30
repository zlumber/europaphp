<?php

class Test_Mongo_Collection extends Europa_Unit_Test
{
    private $_mongo;
    
    private $_db;
    
    public function setUp()
    {
        $this->_mongo = new Europa_Mongo_Connection;
        $this->_db    = $this->_mongo->collectiontest;
        
        for ($i = 1; $i <= 10; $i++) {
            $this->_db->collectiontest->insert(
                array(
                    'field' => 'val' . $i
                )
            );
        }
    }
    
    public function tearDown()
    {
        $this->_db->drop();
    }
    
    public function testGetCursor()
    {
        return $this->_db->collectiontest->getCursor() instanceof MongoCursor;
    }
    
    public function testIteration()
    {
        $items = 0;
        foreach ($this->_db->collectiontest as $item) {
            if (!$item instanceof Collectiontest_Collectiontest) {
                return false;
            }
            ++$items;
        }
        return true && $items === $this->_db->collectiontest->total();
    }
    
    public function testArrayAccessSetWrongClass()
    {
        try {
            $this->_db->collectiontest[] = new Collectiontest_WrongDocument;
        } catch (Europa_Mongo_Exception $e) {
            return true;
        }
        return false;
    }
    
    public function testArrayAccessSet()
    {
        $collection = $this->_db->collectiontest;
        try {
            $collection[] = new Collectiontest_Collectiontest(array('field' => 'val11'));
        } catch (Europa_Mongo_Exception $e) {
            return false;
        }
        return $collection->setPosition($collection->total() - 1)->current()->field === 'val11';
    }
    
    public function testArrayAccessGet()
    {
        return $this->_db->collectiontest[0]->field === 'val1';
    }
    
    public function testArrayAccessExists()
    {
        return isset($this->_db->collectiontest[10]);
    }
    
    public function testArrayAccessUnset()
    {
        unset($this->_db->collectiontest[10]);
        return !isset($this->_db->collectiontest[10]);
    }
    
    public function testExecute()
    {
        $items = $this->_db->collectiontest->execute(array('field' => 'val1'), array('field' => 1, '_id' => 0));
        return $items[0]->field === 'val1' && !isset($items[0]->_id);
    }
    
    public function testWhereValue()
    {
        return $this->_db->collectiontest
             ->where('field', 'val10')
             ->offsetGet(0)
             ->field === 'val10';
    }
    
    public function testWhereCommand()
    {
        $coll = $this->_db->collectiontest->where('field', array('$in' => array('val3', 'val6')));
        return $coll[0]->field === 'val3'
            && $coll[1]->field === 'val6';
    }
    
    public function testGetDb()
    {
        return $this->_db->collectiontest->getDb() === $this->_db;
    }
    
    public function testGetName()
    {
        return $this->_db->collectiontest->getName() === 'collectiontest';
    }
    
    public function testLimit()
    {
        return $this->_db->collectiontest->limit(5)->count() === 5;
    }
    
    public function testSkip()
    {
        $page = $this->_db->collectiontest->limit(1)->skip(2);
        return $page[0]->field === 'val3';
    }
    
    public function testPage()
    {
        $page = $this->_db->collectiontest->limit(5)->page(2);
        return $page[0]->field === 'val6';
    }
    
    public function testGetLimit()
    {
        return $this->_db->collectiontest->limit(7)->getLimit() === 7
            && $this->_db->collectiontest->limit(0)->getLimit() === 10;
    }
    
    public function testGetOffsetWithSkip()
    {
        return $this->_db->collectiontest->limit(2)->skip(2)->getOffset() === 2;
    }
    
    public function testGetOffsetWithPage()
    {
        return $this->_db->collectiontest->limit(3)->page(3)->getOffset() === 6;
    }
    
    public function testGetStartOffsetWithSkip()
    {
        return $this->_db->collectiontest->skip(5)->getStartOffset() === 6;
    }
    
    public function testGetStartoffsetWithLimitAndPage()
    {
        return $this->_db->collectiontest->limit(3)->page(3)->getStartOffset() === 7;
    }
    
    public function testGetEndOffsetWithLimitLessThanCount()
    {
        return $this->_db->collectiontest->limit(5)->getEndOffset() === 5;
    }
    
    public function testGetEndOffsetWithLimitAndPage()
    {
        return $this->_db->collectiontest->limit(4)->page(2)->getEndOffset() === 8;
    }
    
    public function testGetPage()
    {
        return $this->_db->collectiontest->limit(5)->skip(2)->getPage() === 2;
    }
    
    public function testGetTotalPagesWithLimit()
    {
        return $this->_db->collectiontest->limit(3)->getTotalPages() === 4;
    }
    
    public function testGetTotalPagesWithNoLimit()
    {
        return $this->_db->collectiontest->getTotalPages() === 1;
    }
    
    public function testGetStartPageWithLimit()
    {
        return $this->_db->collectiontest->limit(3)->skip(7)->getStartPage(1) === 2;
    }
    
    public function testGetStartPageWithNoLimit()
    {
        return $this->_db->collectiontest->skip(7)->getStartPage() === 2;
    }
    
    public function testGetEndPageWithLimit()
    {
        return $this->_db->collectiontest->limit(3)->skip(7)->getEndPage(1) === 4;
    }
    
    public function testGetEndPageWithNoLimit()
    {
        return $this->_db->collectiontest->skip(7)->getEndPage() === 2;
    }
    
    public function testCount()
    {
        return $this->_db->collectiontest->limit(1)->count() === 1;
    }
    
    public function testTotal()
    {
        return $this->_db->collectiontest->limit(2)->page(3)->total() === 10;
    }
    
    public function testToArray()
    {
        $items = $this->_db->collectiontest->toArray();
        return $items[0]['field'] === 'val1' && is_string($items[0]['_id']);
    }
    
    public function testToMongoArray()
    {
        $items = $this->_db->collectiontest->toMongoArray();
        return $items[9]['field'] === 'val10' && $items[9]['_id'] instanceof MongoId;
    }
    
    public function testSetPosition()
    {
        $coll = $this->_db->collectiontest;
        $coll->setPosition(2);
        return $coll->current()->field === 'val3';
    }
    
    public function testGetPosition()
    {
        $coll = $this->_db->collectiontest;
        $coll->setPosition(2);
        return $coll->getPosition() === 2;
    }
    
    public function testRefreshFlag()
    {
        $coll = $this->_db->collectiontest;
        return $coll->refresh()->hasPendingRefresh() === true
            && $coll->stop()->hasPendingRefresh()    === false;
    }
    
    public function testClassSettingAndGetting()
    {
        $coll = $this->_db->collectiontest->setClass('test');
        return $coll->getClass() === 'test';
    }
}

class Collectiontest_Collectiontest extends Europa_Mongo_Document
{
    
}

class Collectiontest_WrongDocument extends Europa_Mongo_Document
{
    
}