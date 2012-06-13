<?php

class Magazine_Model_DbTable_ArticleContent extends Centurion_Db_Table_Abstract {

    protected $_name = 'magazine_article_content';

    protected $_primary = array('article_id', 'proxy_pk', 'proxy_model');

    protected $_meta = array('verboseName'   => 'article_content',
                             'verbosePlural' => 'article_contents');
    
    protected $_referenceMap = array(
        'article' => array(
            'columns'       => 'article_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Magazine_Model_DbTable_Article',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::CASCADE
        ),
    );
}