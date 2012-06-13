<?php

class Magazine_Form_Model_Media extends Centurion_Form_Model_Abstract implements Magazine_Traits_Form_Model_Interface
{
    public function __construct($options = array())
    {
        $this->_model = Centurion_Db::getSingleton('magazine/media');

        $this->_exclude = array('created_at', 'updated_at', 'articles', 'media_id', 'original_id', 'language_id');

        // Default block
        $this->_elementLabels = array(
            'title'             =>  $this->_translate('Title'),
            'slug'              =>  $this->_translate('Slug'),
            'description'       =>  $this->_translate('body'),
            //'is_online'       =>  $this->_translate('is_online'),
            //'has_comment'          =>  $this->_translate('has_comment'),
            'author_id'         =>  $this->_translate('Author'),
            'media'             =>  $this->_translate('cover'),
            'is_valid'          =>  $this->_translate('is valid ?'),
            'is_contributed'    =>  $this->_translate('is contributed ?'),
        );

        parent::__construct($options);
    }
    
    public function init()
    {
        parent::init();
                
        // Uploader
        $pic = new Media_Form_Model_Admin_File(array('name' => 'media'));
        $pic->getFilename()->getValidator('Size')->setMax(4*1024*1024); 
        $this->addReferenceSubForm($pic, 'media');

        // Title
        $this->getElement('title')->setDescription('Post title');

        // Body
        $this->getElement('description')->setLabel('Description')
                                 ->setAttrib('class', 'field-rte')
                                 ->setAttrib('large', true)
                                 ->removeFilter('StripTags');
    }
}