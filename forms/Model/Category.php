<?php

class Magazine_Form_Model_Category extends Centurion_Form_Model_Abstract
{
    public function __construct($options = array())
    {
        $this->_model = Centurion_Db::getSingleton('magazine/category');

        $this->_exclude = array('language_id', 'original_id', 'created_at', 'updated_at', 'online_articles', 'cover_id', 'parent_id', 'icon_id');

        // Default block
        $this->_elementLabels = array(
            'name'               =>  $this->_translate('name'),
            'slug'               =>  $this->_translate('slug'),
            'body'               =>  $this->_translate('body'),
            'css_class'          =>  $this->_translate('css_class'),
            'pagination_item'    =>  $this->_translate('pagination_item'),
            'is_online'          =>  $this->_translate('is_online'),
            'cover'              =>  $this->_translate('cover'),
            'icon'               =>  $this->_translate('icon'),
            'parent'             =>  $this->_translate('parent'),
            'articles'           =>  $this->_translate('articles'),
        );

        parent::__construct($options);
    }

    public function init() {
        parent::init();
        // Uploader
        $pic = new Media_Form_Model_Admin_File(array('name' => 'cover'));
        $pic->getFilename()->getValidator('Size')->setMax(4*1024*1024);
        $this->addReferenceSubForm($pic, 'cover');

        $pic = new Media_Form_Model_Admin_File(array('name' => 'icon'));
        $pic->getFilename()->getValidator('Size')->setMax(4*1024*1024);
        $this->addReferenceSubForm($pic, 'icon');

        // Body
        $this->getElement('body')->setLabel('')
                                 ->setAttrib('class', 'field-rte')
                                 ->setAttrib('large', true)
                                 ->removeFilter('StripTags');
    }
}
