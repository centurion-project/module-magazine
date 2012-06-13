<?php

class Magazine_Traits_Model_DbTable extends Core_Traits_Version_Model_DbTable
{
    public function init()
    {
        parent::init();

        $manyDependentTables = $this->_manyDependentTables;

        $manyDependentTables['tags'] = array(
            'refTableClass'     =>  'Magazine_Model_DbTable_Tag',
            'intersectionTable' =>  'Magazine_Model_DbTable_TagProxy',
            'reflocal'     =>  'media',
            'refforeign'   =>  'tag',
        );

        $this->_manyDependentTables = $manyDependentTables;

        $table = Centurion_Db::getSingleton('magazine/tag_proxy');

        $refs = $table->getReferenceMap();

        $refs['media'] = array(
            'columns'       => array('proxy_pk', 'proxy_model'),
            'refColumns'    => array('id', 'content_type_id'),
            'refTableClass' => 'Magazine_Model_DbTable_Media',
            'onDelete'      => Zend_Db_Table_Abstract::CASCADE,
            'onUpdate'      => Zend_Db_Table_Abstract::CASCADE,
        );

        $table->setReferences($refs);
    }
}