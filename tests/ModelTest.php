<?php

require_once dirname(__FILE__) . '/../../../../tests/TestHelper.php';

class Magazine_Test_ModelTest extends PHPUnit_Framework_TestCase {
    
    public function createArticle($authorRow)
    {
        $articleTable = Centurion_Db::getSingleton('magazine/article');
        $instance = $articleTable->createRow();
        $instance->content_type_id = $instance->getContentTypeId();
        $instance->title = 'Test';
        $instance->author_id = $authorRow->id;
        $instance->save();
        
        return $instance;
    }
    
    public function createAuthor()
    {
        $authorTable = Centurion_Db::getSingleton('magazine/author');
        
        $authorRow = $authorTable->createRow();
        $authorRow->lastname = 'test';
        $authorRow->firstname = 'test';
        $authorRow->save();
        
        return $authorRow;
    }

    public function test()
    {
        $this->markTestIncomplete();
    }

}

