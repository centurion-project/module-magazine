<?php

class Magazine_View_Helper_GetLastArticle extends Zend_View_Helper_Abstract
{

    /**
     * @param int $magazineId
     * @param int $limit
     * @return mixed
     * @todo : factoriser vues
     */
    public function getLastArticle($magazineId = 1, $limit = 3)
    {
        return Magazine_Model_MagazineArticle::getAllMagazineArticle($magazineId, false, true, $limit);
    }
}
