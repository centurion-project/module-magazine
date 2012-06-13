<?php
class Magazine_View_Helper_GetRelatedArticleByTags extends Zend_View_Helper_Abstract
{

    public function getRelatedArticleByTags($row)
    {
        return Magazine_Model_MagazineArticle::getRelatedArticleByTags($row);
    }

}