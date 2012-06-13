<?php
class Magazine_View_Helper_GetPromotedPosts extends Zend_View_Helper_Abstract
{
    
    public function getPromotedPosts($filter = array(), $limit = null, $orderby = 'published_at DESC') 
    {
        $postTable = Centurion_Db::getSingleton('magazine/article');
                
        $select = $postTable->getPostSelect();
        
        $filter = array_merge($filter, array('is_promoted' => 1));
        
        $select->filter($filter);

        $select->order($orderby);
        
        if ($limit != null)
            $select->limit($limit);
            
        return $select->fetchAll();
    }
    
}