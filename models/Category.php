<?php

class Magazine_Model_Category
{
    /**
     * Retourne toutes les categories
     *
     * @static
     * @param $slug
     * @return mixed
     * @todo : ne pas remonter les categories sans articles
     * @todo : filtrer par magazine
     */
    static public function getCategoryBySlug($slug)
    {
        return Centurion_Db::getSingleton('magazine/category')->findOneBySlug($slug);
    }
}