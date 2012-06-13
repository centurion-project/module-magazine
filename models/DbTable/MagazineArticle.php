<?php

class Magazine_Model_DbTable_MagazineArticle extends Centurion_Db_Table_Abstract
{

    protected $_primary = array('article_id', 'magazine_id');

    protected $_name = 'magazine_magazine_article';

    protected $_rowClass = 'Magazine_Model_DbTable_Row_MagazineArticle';

    protected $_meta = array('verboseName'   => 'magazine_article',
                             'verbosePlural' => 'magazine_articles');
    
    protected $_referenceMap = array(
        'article' => array(
            'columns' => 'article_id',
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
        /**
         * The reference return a row if this article is an headline
         */
        'parent' => array(
            'columns' => array('article_id', 'magazine_id'),
            'refColumns' => array('parent_id', 'magazine_id'),
            'refTableClass' => 'Magazine_Model_DbTable_ArticleArticle',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::CASCADE
        ),
        /**
         * The reference return a row if this article is a child of an headline
         */
        'child' => array(
            'columns' => array('article_id', 'magazine_id'),
            'refColumns' => array('child_id', 'magazine_id'),
            'refTableClass' => 'Magazine_Model_DbTable_ArticleArticle',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::CASCADE
        ),
    );
}