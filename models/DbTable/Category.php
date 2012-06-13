<?php
class Magazine_Model_DbTable_Category extends Centurion_Db_Table_Abstract
{
    protected $_name = 'magazine_category';
    
    protected $_primary = 'id';
    
    protected $_rowClass = 'Magazine_Model_DbTable_Row_Category';
    
    protected $_meta = array('verboseName'   => 'category',
                             'verbosePlural' => 'categories');

    protected $_referenceMap = array(
        'cover'             =>  array(
            'columns'       => 'cover_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Media_Model_DbTable_File',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::RESTRICT
        ),
        'icon'              =>  array(
            'columns'       => 'icon_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Media_Model_DbTable_File',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::RESTRICT
        ),
        'parent'            =>  array(
            'columns'       => 'parent_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Magazine_Model_DbTable_Category',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::RESTRICT
        ),
    );

    protected $_manyDependentTables = array(
        'articles' => array(
            'refTableClass'     =>  'Magazine_Model_DbTable_Article',
            'intersectionTable' =>  'Magazine_Model_DbTable_ArticleCategory',
            'reflocal'          =>  'category',
            'refforeign'        =>  'article'
        ),
    );
}
