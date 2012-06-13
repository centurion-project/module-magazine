<?php

class Magazine_Form_Model_Article extends Centurion_Form_Model_Abstract implements Magazine_Traits_Form_Model_Interface
{
    public function __construct($options = array())
    {
        $this->_model = Centurion_Db::getSingleton('magazine/article');

        $this->getPluginLoader(self::DECORATOR)->addPrefixPath( 'MultiselectOrder_Form_Decorator', APPLICATION_PATH.'/modules/multiselectorder/forms/Decorator' );

        $this->_exclude = array('language_id', 'original_id', 'created_at', 'updated_at', 'content_type_id', 'cover_id', 'child', 'parent');

        // Default block
        $this->_elementLabels = array(
            'title'             =>  $this->_translate('Title'),
            'slug'              =>  $this->_translate('Slug'),
            'subtitle'          =>  $this->_translate('Subtitle'),
            'abstract'          =>  $this->_translate('Chapô'),
            'body'              =>  $this->_translate('Body'),
            'video_link'        =>  $this->_translate('Video'),
            'is_online'         =>  $this->_translate('Online'),
            'is_promoted'       =>  $this->_translate('Promoted'),
            'is_headline'       =>  $this->_translate('Headline'),
            'has_comment'       =>  $this->_translate('Comments'),
            'has_twitter'       =>  $this->_translate('Share Twitter'),
            'has_facebook'      =>  $this->_translate('Share Facebook'),
            'has_push_social'   =>  $this->_translate('Push social'),
            'has_related'       =>  $this->_translate('Related objects'),
            'author_id'         =>  $this->_translate('Author'),
            'cover'             =>  $this->_translate('Cover'),
            'picture'           =>  $this->_translate('Picture'),
            'published_at'      =>  $this->_translate('Publish date'),
            'categories'        =>  $this->_translate('Categories'),
            'magazines'         =>  $this->_translate('Mag'),
            'evenements'        =>  $this->_translate('Evenements'),
            'is_article'        =>  $this->_translate('Article'),
            'citation'          =>  $this->_translate('citation'),
            'is_contributed'    =>  $this->_translate('Is contributed ?'),
            'is_homepage'       =>  $this->_translate('Afficher sur la Homepage'),
        );

        parent::__construct($options);
    }
    
    public function init()
    {
        parent::init();
                
        // Uploader
        $pic = new Media_Form_Model_Admin_File(array('name' => 'cover'));
        $pic->getFilename()->getValidator('Size')->setMax(4*1024*1024); 
        $this->addReferenceSubForm($pic, 'cover');

        // Uploader
        $pic = new Media_Form_Model_Admin_File(array('name' => 'picture'));
        $pic->getFilename()->getValidator('Size')->setMax(4*1024*1024);
        $this->addReferenceSubForm($pic, 'picture');
        
        // Title
        $this->getElement('title')->setDescription('Post title');

        $this->getElement('subtitle')->setDescription('Subtitle');

        // Body
        $this->getElement('body')->setLabel('')
                                 ->setAttrib('class', 'field-rte')
                                 ->setAttrib('large', true)
                                 ->removeFilter('StripTags');

        // Video
        $this->getElement('video_link')->setDescription('URL complète : http://www.youtube.com/watch?v=xFaQyucfnc4s');

        // Published at
        //$this->getElement('published_at')->setAttrib('class', 'field-datepicker');

        // Headline
        //TODO : Fix populate
        /*$this->removeElement('childs');
        $articleSubForm = new Magazine_Form_Model_Admin_ManyToManyWithOrder(array(
            'name'              => 'childs',
            'title'             => $this->_translate('Articles'),
            'description'       => $this->_translate('Choose and order articles'),
            'model'             => $this->_model
        ));
        $this->addSubForm($articleSubForm, 'childs');*/
    }

    public function setInstance(Centurion_Db_Table_Row_Abstract $instance = null)
    {
        parent::setInstance($instance);

        $this->getElement('childs')->setDecorators(array( array('Multiselect' => 'MultiselectOrder') ) );

        // Format publication date : YYYY-MM-dd HH:mm:ss > MM/dd/yyyy
        /*if ($instance) {
            $this->getElement('published_at')->setValue($instance->getDateObjectByPublished_at()->get('MM/dd/yy'));
        } else {
            $date = new Zend_Date(time());
            $this->getElement('published_at')->setValue($date->get('MM/dd/yy'));
        }*/


        return $this;
    }

    public function saveInstance($values = null)
    {
        if ($values === null) {
            $values = $this->getValues();
        }

        /*$posted_at              = new Zend_Date($values['published_at'], 'MM/dd/yy');
        $values['published_at'] = $posted_at->get('YYYY-MM-dd HH:mm:ss');*/

        $instance = parent::saveInstance($values);

        return $instance;
    }
}
