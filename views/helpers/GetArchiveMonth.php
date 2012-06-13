<?php
class Blog_View_Helper_GetArchiveMonth extends Zend_View_Helper_Abstract
{
    public function getArchiveMonth()
    {
        $post = Centurion_Db::getSingleton('blog/post');
        $select = $post->select(true);
        $select->columns(array('archive_date' => new Zend_Db_Expr('CONCAT(MONTH(published_at), YEAR(published_at))')));
        
        $month = $post->getCounts('archive_date', $select);
        
        return $month;
    }
}