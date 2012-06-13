<?php
class Magazine_Model_DbTable_Magazine extends Centurion_Db_Table_Abstract
{
    protected $_name = 'magazine_magazine';

    protected $_primary = 'id';

    protected $_rowClass = 'Magazine_Model_DbTable_Row_Magazine';

    protected $_meta = array('verboseName'   => 'magazine',
                             'verbosePlural' => 'magazines');


    protected $_referenceMap = array(
        'logo'             =>  array(
            'columns'       => 'logo_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Media_Model_DbTable_File',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::RESTRICT
        ),
    );

   protected $_manyDependentTables = array(
        'articles'   =>  array('refTableClass'     =>  'Magazine_Model_DbTable_Article',
                          'intersectionTable' =>  'Magazine_Model_DbTable_ArticleMagazine',
                          'columns'           =>  array('local'     =>  'article_id',
                                                        'foreign'   =>  'magzine_id')),
   );
}
