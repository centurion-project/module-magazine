<?php
class Magazine_Model_DbTable_TagProxy extends Centurion_Db_Table_Abstract
{
    protected $_name = 'magazine_tag_proxy';
    
    protected $_meta = array('verboseName'   => 'tagProxy',
                             'verbosePlural' => 'tagProxies');


    protected $_referenceMap = array(
        'tag'   =>  array(
            'columns'       => 'tag_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Magazine_Model_DbTable_Tag',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::CASCADE
        ),
        'article'   =>  array(
            'columns'       => array('proxy_pk', 'proxy_model'),
            'refColumns'    => array('id', 'content_type_id'),
            'refTableClass' => 'Magazine_Model_DbTable_Article',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::CASCADE
        ),
        'media'   =>  array(
            'columns'       => array('proxy_pk', 'proxy_model'),
            'refColumns'    => array('id', 'content_type_id'),
            'refTableClass' => 'Magazine_Model_DbTable_Media',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::CASCADE
        ),
    );
}
