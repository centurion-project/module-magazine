<?php

class Magazine_Model_DbTable_Author extends Centurion_Db_Table_Abstract
{
    protected $_name = 'magazine_author';

    protected $_primary = 'id';

    protected $_rowClass = 'Magazine_Model_DbTable_Row_Author';

    protected $_meta = array('verboseName'   => 'author',
                             'verbosePlural' => 'authors');

    protected $_referenceMap = array(
        'picture' =>  array(
            'columns'       => 'picture_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Media_Model_DbTable_File',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::RESTRICT
        ),
        'user' =>  array(
            'columns'       => 'user_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Auth_Model_DbTable_User',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::RESTRICT
        ),
    );
}
