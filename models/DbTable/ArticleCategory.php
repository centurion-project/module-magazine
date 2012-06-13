<?php

class Magazine_Model_DbTable_ArticleCategory extends Centurion_Db_Table_Abstract {

    protected $_name = 'magazine_article_category';

    protected $_primary = array('category_id', 'article_id');

    protected $_meta = array('verboseName'   => 'article_category',
                             'verbosePlural' => 'article_categories');
    
    protected $_referenceMap = array(
        'article' => array(
                'columns' => 'article_id',
                'refColumns' => 'id',
                'refTableClass' => 'Magazine_Model_DbTable_Article',
                'onDelete'      => self::CASCADE,
                'onUpdate'      => self::CASCADE
        ),
        'category' => array(
                'columns' => 'category_id',
                'refColumns' => 'id',
                'refTableClass' => 'Magazine_Model_DbTable_Category',
                'onDelete'      => self::CASCADE,
                'onUpdate'      => self::CASCADE
        ),
    );
}