<?php

class Magazine_Traits_Form_Model extends Centurion_Traits_Form_Model_Abstract
{
    public function init()
    {
        $this->removeElement('tags');
        $this->addElement('multiselect', 'tags', array('label' => $this->_translate('Tags')));
        
        $multiOptions = Magazine_Model_Tag::getTagsListForMultiOptions();
        
        $tagElement = $this->getElement('tags');

        $tagElement->setMultiOptions($multiOptions);

        $tagElement->setLabel('Tags');

        //Add news tags
        $newTags = new Zend_Form_Element_Text('newtags');
        $newTags->setLabel('Add tags')
            ->setDescription('tag, tag, tag, tag');
        $this->addElement($newTags, 'newtags');
    }
    
    public function _postSave()
    {
        $values = $this->getValues();
    
        $instance = $this->getInstance();
        
        // Insert new tags and add the relation with this post
        if(!empty($values['newtags'])){
            // Get post id
            $proxyPk = $instance->id;
            $proxyModel = Centurion_Db::getSingleton('core/contentType')->getContentTypeIdOf($instance);
    
            // Get all new tags
            $arrTags = explode(', ', $values['newtags']);
    
            $tagTable = Centurion_Db::getSingleton('magazine/tag');
            $postTagTable = Centurion_Db::getSingleton('magazine/tag_proxy');
    
            // Get or create tag & insert the relation
            foreach ($arrTags as $t){
                // Get or create tag
                $data = array('name' => $t);
                list($newTagRow, ) = $tagTable->getOrCreate($data);
    
                // Get or create the relation Post <--> Tag
                $data = array(
                        'proxy_pk' => $proxyPk,
                        'proxy_model' => $proxyModel,
                        'tag_id' => $newTagRow->id);
                $postTagTable->insertIfNotExist($data);
            }
        }
    
        return $instance;
    }
}