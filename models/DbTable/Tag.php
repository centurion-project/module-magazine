<?php
class Magazine_Model_DbTable_Tag extends Centurion_Db_Table_Abstract
{
    protected $_name = 'magazine_tag';
    
    protected $_primary = 'id';
    
    protected $_rowClass = 'Magazine_Model_DbTable_Row_Tag';
    
    protected $_dependentTables = array(
        'tagProxies' => 'Magazine_Model_DbTable_TagProxy'
    );
    
    protected $_meta = array('verboseName'   => 'tag',
                             'verbosePlural' => 'tags');

    protected $_manyDependentTables = array(
        'articles'       => array(
            'refTableClass'     =>  'Magazine_Model_DbTable_Article',
            'intersectionTable' =>  'Magazine_Model_DbTable_TagProxy',
            'reflocal'       =>  'tag',
            'refforeign'     =>  'article',
        ),
        'medias'       => array(
            'refTableClass'     =>  'Magazine_Model_DbTable_Media',
            'intersectionTable' =>  'Magazine_Model_DbTable_TagProxy',
            'reflocal'       =>  'tag',
            'refforeign'     =>  'media',
        ),
    );
}
