<?php

class Magazine_Form_Model_Magazine extends Centurion_Form_Model_Abstract
{
    public function __construct($options = array())
    {
        $this->_model = Centurion_Db::getSingleton('magazine/magazine');

        $this->_exclude = array('language_id', 'original_id', 'created_at', 'updated_at', 'articles', 'logo_id');

        // Default block
        $this->_elementLabels = array(
            'title'             =>  $this->_translate('Title'),
            'slug'              =>  $this->_translate('Slug'),
            'subtitle'              =>  $this->_translate('Subtitle'),
            'logo'          =>  $this->_translate('Logo'),
        );

        parent::__construct($options);
    }
    
    public function init()
    {
        parent::init();
                
        // Uploader
        $pic = new Media_Form_Model_Admin_File(array('name' => 'logo'));
        $pic->getFilename()->getValidator('Size')->setMax(4*1024*1024); 
        $this->addReferenceSubForm($pic, 'logo');
    }
}