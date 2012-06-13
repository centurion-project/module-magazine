<?php

class Magazine_Model_DbTable_Row_Tag extends Centurion_Db_Table_Row_Abstract implements Core_Traits_Slug_Model_DbTable_Row_Interface
{
    public function __toString()
    {
        return $this->name;
    }
    
    public function getSlugifyName()
    {
        return array('name');
    }
}