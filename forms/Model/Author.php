<?php

class Magazine_Form_Model_Author extends Centurion_Form_Model_Abstract
{
    public function __construct($options = array())
    {
        $this->_model = Centurion_Db::getSingleton('magazine/author');

        $this->_exclude = array('language_id', 'original_id', 'created_at', 'updated_at', 'picture_id');

        // Default block
        $this->_elementLabels = array(
            'lastname'              =>  $this->_translate('lastname'),
            'firstname'             =>  $this->_translate('firstname'),
            'slug'                  =>  $this->_translate('slug'),
            'abstract'              =>  $this->_translate('abstract'),
            'body'                  =>  $this->_translate('body'),
            'url_twitter'           =>  $this->_translate('url_twitter'),
            'url_facebook'          =>  $this->_translate('url_facebook'),
            'url_googleplus'        =>  $this->_translate('url_googleplus'),
            'url_blog'              =>  $this->_translate('url_blog'),
            'user'                  =>  $this->_translate('user'),
            'picture'               =>  $this->_translate('picture'),
            'is_contributor'        =>  $this->_translate('is contributor ?'),
        );

        parent::__construct($options);
    }
    
    public function init()
    {
        parent::init();
                
        // Uploader
        $pic = new Media_Form_Model_Admin_File(array('name' => 'picture'));
        $pic->getFilename()->getValidator('Size')->setMax(4*1024*1024); 
        $this->addReferenceSubForm($pic, 'picture');

        // Title
        $this->getElement('lastname')->setDescription('Lastname');

        $this->getElement('firstname')->setDescription('Firstname');

        // Body

        $this->getElement('abstract')->setLabel('')
                                     ->setAttrib('large', true)
                                     ->setDescription('Abstract');
        
        $this->getElement('body')->setLabel('')
                                 ->setAttrib('class', 'field-rte')
                                 ->setAttrib('large', true)
                                 ->removeFilter('StripTags');
        
    }
}