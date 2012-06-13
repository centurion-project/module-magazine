<?php

class Magazine_Model_DbTable_ArticleArticle extends Centurion_Db_Table_Abstract {

    protected $_primary = array('parent_id', 'child_id');

    protected $_name = 'magazine_article_article';

    protected $_rowClass = 'Magazine_Model_DbTable_Row_ArticleArticle';

    protected $_meta = array('verboseName'   => 'article_article',
                             'verbosePlural' => 'article_articles');

    //TODO : régler problème referenceMap
    protected $_referenceMap = array(
        'parent' => array(
            'columns' => array('parent_id', 'magazine_id'),
            'refColumns' => array('article_id', 'magazine_id'),
            'refTableClass' => 'Magazine_Model_DbTable_MagazineArticle',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::CASCADE
        ),
        'child' => array(
            'columns' => array('child_id', 'magazine_id'),
            'refColumns' => array('article_id', 'magazine_id'),
            'refTableClass' => 'Magazine_Model_DbTable_MagazineArticle',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::CASCADE
        ),
        'parent2' => array(
            'columns' => 'parent_id',
            'refColumns' => 'id',
            'refTableClass' => 'Magazine_Model_DbTable_Article',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::CASCADE
        ),
        'child2' => array(
            'columns' => 'child_id',
            'refColumns' => 'id',
            'refTableClass' => 'Magazine_Model_DbTable_Article',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::CASCADE
        ),
        'magazine' => array(
                'columns' => 'magazine_id',
                'refColumns' => 'id',
                'refTableClass' => 'Magazine_Model_DbTable_Magazine',
                'onDelete'      => self::CASCADE,
                'onUpdate'      => self::CASCADE
        ),
    );
}