<?php

class Magazine_Model_ArticleArticle
{
    /**
     * @static
     * @param $magazineId
     * @return Zend_Db_Select
     * @todo : remplacer par Magazine_Model_MagazineArticle::getSelectOnlineHeadline();
     */
    static public function getSelectOnlineParent($magazineId)
    {
        $magazineId = Magazine_Model_MagazineArticle::getIdCleanOf($magazineId);

        $select = Centurion_Db::getSingleton('magazine/article_article')
            ->select(true)
            ->filter(array(
                // Dossier & en ligne
                'parent__is_online' => 1,
                'parent__is_headline' => 1,
                // Réstriction lié à la date
                '!parent__published_at' => '0000-00-00 00:00:00',
                'parent__published_at__lt' => new Zend_Db_Expr('NOW()'),
                // Existe dans un magazine
                'magazine_id' => $magazineId,
            ))
            ->order('parent__published_at DESC');

        return $select;
    }

    /**
     * Get an headline
     *
     * @static
     * @param $magazineId
     * @param $slug
     * @return null|Zend_Db_Table_Row_Abstract
     * @todo : à remplacer part Magazine_Model_MagazineArticle::getHeadlineBySlug()
     */
    static public function getParentArticleArticleBySlug($magazineId, $slug)
    {
        $select = self::getSelectOnlineParent($magazineId);

        $select->filter(array(
                'parent__slug' => $slug
            ));

        return $select->fetchRow();
    }

    /**
     * Get all childs of an headline
     *
     * @static
     * @param $magazineId
     * @param $parentId
     * @return Zend_Db_Table_Rowset_Abstract
     * @todo : à remplacer part Magazine_Model_MagazineArticle::getHeadlineChilds()
     */
    static public function getChilds($magazineId, $parentId)
    {
        $magazineId = Magazine_Model_MagazineArticle::getIdCleanOf($magazineId);
        $parentId = Magazine_Model_MagazineArticle::getIdCleanOf($parentId);

        $select = Centurion_Db::getSingleton('magazine/article_article')
            ->select(true)
            ->filter(array(
                'parent_id' => $parentId,
                'child__is_online' => 1,
                '!child__published_at' => '0000-00-00 00:00:00',
                'child__published_at__lt' => new Zend_Db_Expr('NOW()'),
                'magazine_id' => $magazineId,
            ));

        return $select->fetchAll();
    }
}