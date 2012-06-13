<?php

class Magazine_View_Helper_GetMagazine extends Zend_View_Helper_Abstract
{

    /**
     * @param int $magazineId
     * @param int $limit
     * @return mixed
     * @todo : factoriser vues
     */
    public function getMagazine()
    {
        return Magazine_Model_Magazine::getDefaultMagazine();
    }
}
