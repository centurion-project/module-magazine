<?php
class Magazine_Model_DbTable_Media extends Centurion_Db_Table_Abstract implements Magazine_Traits_Model_DbTable_Interface
{
    protected $_name = 'magazine_media';
    
    protected $_primary = 'id';
    
    protected $_rowClass = 'Magazine_Model_DbTable_Row_Media';
    
    protected $_meta = array('verboseName'   => 'media',
                             'verbosePlural' => 'medias');
    
    protected $_referenceMap = array(
        'media'             =>  array(
            'columns'       => 'media_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Media_Model_DbTable_File',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::RESTRICT
        ),
        'author' => array(
            'columns'       => 'author_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Magazine_Model_DbTable_Author',
            'onDelete'      => self::SET_NULL,
            'onUpdate'      => self::CASCADE,
        ),
    );
    
   protected $_manyDependentTables = array(
        'articles'   =>  array(
            'refTableClass'     =>  'Magazine_Model_DbTable_Article',
            'intersectionTable' =>  'Magazine_Model_DbTable_ArticleMedia',
            'columns'           =>  array(
                'local'     =>  'article_id',
                'foreign'   =>  'media_id'
            )
        ),
        'tags'       => array(
            'refTableClass'     =>  'Magazine_Model_DbTable_Tag',
            'intersectionTable' =>  'Magazine_Model_DbTable_TagProxy',
            'reflocal'       =>  'media',
            'refforeign'     =>  'tag',
        )
    );
}
