<?php

class Magazine_Model_DbTable_Row_Author extends Centurion_Db_Table_Row_Abstract implements Core_Traits_Slug_Model_DbTable_Row_Interface
{
    public function init()
    {
        parent::init();

        $this->_specialGets['fullname'] = 'getFullName';
    }

    public function __toString()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
    
    public function getSlugifyName()
    {
        return array('firstname', 'lastname');
    }

    /**
     * Retourne le nom complet de l'auteur : firstname lastname
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}