<?php

class Magazine_Form_Model_Tag extends Centurion_Form_Model_Abstract
{
    public function __construct($options = array())
    {
        $this->_model = Centurion_Db::getSingleton('magazine/tag');

        $this->_exclude = array('language_id', 'original_id', 'created_at', 'updated_at');

        // Default block
        $this->_elementLabels = array(
            'name'             =>  $this->_translate('Name'),
            'slug'             =>  $this->_translate('Slug'),
            'articles'             =>  $this->_translate('Articles'),
            'evenements'             =>  $this->_translate('Evenements'),
            'medias'             =>  $this->_translate('Medias'),
        );

        parent::__construct($options);
    }
}