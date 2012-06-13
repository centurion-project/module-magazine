<?php
class Magazine_View_Helper_GetPopularTags extends Zend_View_Helper_Abstract
{
    
    public function getPopularTags($limit = null)
    {
        
        $tag = Centurion_Db::getSingleton('magazine/tag');
        
        $popular = $tag->getCounts('post_tags__tag_id', null, $limit);
                        
        return $popular;
                
    }
    
}