<?php
class Magazine_View_Helper_GetLatestPosts extends Zend_View_Helper_Abstract
{
    
    public function getLatestPosts($filter = array(), $limit = null) 
    {
        $postTable = Centurion_Db::getSingleton('magazine/article');
                            
        return $postTable->getLatestPosts($filter, $limit);
    }
    
}