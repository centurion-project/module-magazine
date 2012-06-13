<?php

class Magazine_Model_Magazine
{
    /**
     * @static
     * @param $slug
     * @return null|Zend_Db_Table_Row_Abstract
     */
    static public function getMagazineBySlug($slug)
    {
        $select = Centurion_Db::getSingleton('magazine/magazine')
            ->select(true)
            ->filter(array('slug' => $slug));

        return $select->fetchRow();
    }

    /**
     * Dans le cadre d'InterRhône, il nous est possible d'écrire cette méthode
     * car le projet ne disposera que d'un seul magazine.
     *
     * @static
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public static function getDefaultMagazine()
    {
        return Centurion_Db::getSingleton('magazine/magazine')->select(true)->fetchRow();
    }
}