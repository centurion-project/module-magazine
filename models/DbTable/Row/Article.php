<?php

class Magazine_Model_DbTable_Row_Article extends Centurion_Db_Table_Row_Abstract implements Core_Traits_Slug_Model_DbTable_Row_Interface
{
    public function __toString()
    {
        return $this->title;
    }
    
    public function getSlugifyName()
    {
        return array('title');
    }

    /**
     * Méthode temporaire (voir commentaire dans le fichier de la class Magazine_Model_DbTable_Article)
     * Retourne la row de magazine_magazine_article correspondante à l'article
     *
     * @return mixed
     */
    public function getMagazineArticleRow()
    {
        return Centurion_Db::getSingleton('magazine/magazine_article')->findOneByArticle_id($this->id);
    }
}