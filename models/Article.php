<?php

/**
 * @deprecated Utiliser Magazine_Model_MagazineArticle
 * @todo : A supprimer
 */
class Magazine_Model_Article
{
    /**
     * Permet de générer le select par defaut pour récuperer les articles en ligne dans le magazine courant
     *
     * @static
     * @param $magazineId
     * @returngetNext mixed
     */
    static public  function getSelectOnlineArticle($magazineId)
    {
        $articleSelect = Centurion_Db::getSingleton('magazine/article')
            ->select(true)
            ->join('magazine_magazine_article', 'magazine_article.id = magazine_magazine_article.article_id', array())
            ->filter(array(
                'is_online' => 1,
                '!published_at__isnull' => true,
                '!published_at' => '0000-00-00 00:00:00',
                'published_at__lt' => new Zend_Db_Expr('NOW()')
            ))
            ->order('published_at DESC');

        if (null !== $magazineId) {
            //TODO : Voir pourquoi magazine_id déconne dans le filter
            $articleSelect->where('magazine_id = ?', $magazineId);
        }

        return $articleSelect;
    }

    /**
     * Récupère un article en fonction du magazine et d'un slug
     *
     * @static
     * @param $magazineRow
     * @param $articleSlug
     * @return mixed
     */
    static public function getArticle($magazineRow, $articleSlug)
    {
         $articleRow = self::getSelectOnlineArticle($magazineRow->id)
             ->filter(array(
                 'slug' => $articleSlug,
             ))
             ->fetchRow();

        return $articleRow;
    }

    /**
     * Récupère tous les articles d'un article
     *
     * @static
     * @param $magazineRow
     * @param bool $fetchChildFolder
     * @param null $limit
     * @param bool $orderByView
     * @return mixed
     */
    static public function getAllArticle($magazineRow, $fetchChildFolder = false, $limit = null, $orderByView = false)
    {
        $allArticleSelect = self::getSelectOnlineArticle($magazineRow->id)
            ->setIntegrityCheck(false)
            ->filter(array('is_headline' => 0));

        // Récupération des enfants d'un dossier
        if ($fetchChildFolder == false) {
            $excludeArticleRowset = Centurion_Db::getSingleton('magazine/article_article')
                ->select(true)
                ->reset(Zend_Db_Table_Select::COLUMNS)
                ->columns('child_id')
                ->where('magazine_id = ?', $magazineRow->id)
                ->orWhere('magazine_id IS NULL')
                ->fetchAll();

            // Si il y a des articles à exclure, ajout d'un filtre avec les id des articles concernés
            if (0 < $excludeArticleRowset->count()) {
                $excludeIdArray = array();
                foreach ($excludeArticleRowset as $excludeArticleRow) {
                    $excludeIdArray[] = $excludeArticleRow->child_id;
                }

                $allArticleSelect->filter(array('!id__in' => $excludeIdArray));
            }
        }

        if (null !== $limit) {
            $allArticleSelect->limit($limit);
        }

        if (false !== $orderByView) {
            $allArticleSelect->order('view DESC');
        }

        return $allArticleSelect->fetchAll();
    }

    /**
     * Récupère le dernier dossier en ligne
     *
     * @static
     * @param $magazineRow
     * @return mixed
     */
    static public function getLastHeadline($magazineRow)
    {
        $articleSelect = self::getSelectOnlineArticle($magazineRow->id)
            ->filter(array(
                'is_headline' => 1
            ));

        return $articleSelect->fetchRow();
    }

    /**
     * Récupère les enfants d'un dossier en fonction de l'id
     *
     * @static
     * @param $articleId
     * @return mixed
     * @todo : contrainte par magazine
     */
    public static function getArticlesForHeadline($articleId)
    {
        if ($articleId instanceof Zend_Db_Table_Row_Abstract) {
            $articleId = $articleId->id;
        }
        
        return Centurion_Db::getSingleton('magazine/article')->select(true)
            ->join('magazine_article_article', 'parent_id = ' . $articleId . ' and child_id = magazine_article.id', array())
            ->order('magazine_article_article.order asc')
            ->fetchAll();
    }

    /**
     * Récpère la catégorie
     *
     * @static
     * @param $articleRow
     * @return mixed
     * @todo : contrainte par magazine
     * @todo : gestion dans le cas de plusieurs catégorie (voir avec le multiselect with order)
     */
    static public function getMainCategory($articleRow)
    {
        if (null === $articleRow) {
            die('ici');
        }
    
        $categoryRow = Centurion_Db::getSingleton('magazine/category')
            ->select(true)
            ->setIntegrityCheck(false)
            ->join('magazine_article_category', 'id = category_id')
            ->where('article_id = ?', $articleRow->id)
            ->fetchRow();
            
        return $categoryRow;
    }

    /**
     * @static
     * @return mixed
     * @todo : contrainte de magazine
     * @deprecated
     */
    static public function getChild()
    {
        $articleRowset = Centurion_Db::getSingleton('magazine/article')
            ->select(true)
            ->setIntegrityCheck(false)
            ->join('magazine_article_article', 'magazine_article.id = parent_id')
            ->join('magazine_article', ' magazine_article_2.id = child_id')

            ->order('order DESC')
            ->fetchAll();

        return $articleRowset;
    }

    /**
     * Vérifie si l'article a un parent (fait parti d'un dossier)
     *
     * @static
     * @param $articleRow
     * @return bool
     * @todo : contrainte par magazine
     */
    static public function hasParent($articleRow)
    {
        $articleRow = Centurion_Db::getSingleton('magazine/article_article')
            ->select(true)
            ->filter(array('child_id' => $articleRow->id))
            ->fetchRow();

        return (null !== $articleRow) ? true : false;
    }

    /**
     * Récupère l'article parent (le dossier)
     *
     * @static
     * @param $articleRow
     * @return mixed
     */
    static public function getParent($articleRow)
    {
        $select = Centurion_Db::getSingleton('magazine/article_article')
            ->select(true)
            ->setIntegrityCheck(false)
            ->join('magazine_article', 'parent_id = id')
            ->where('child_id = ?', $articleRow->id);

        return $select->fetchRow();
    }

    /**
     * @static
     * @param $articleRow
     * @param bool $headlineRow
     * @return mixed
     * @todo : Ajouter contrainte magazine
     * @todo : à finir (voir getPreviousArticle fait par Lahcen)
     */
    static public function getArticlePrevRow($articleRow, $headlineRow = false)
    {
        $select = self::getSelectOnlineArticle(null)
             ->filter(array(
                 'published_at__lt' => $articleRow->published_at
             ))
             ->order('published_at DESC');

        if (false !== $headlineRow) {
            $select->setIntegrityCheck(false)
                ->join('magazine_article_article', 'parent_id = ' . $headlineRow->id);
        }

        //echo $select;die;

        return $select->fetchRow();
    }

    /**
     * @static
     * @param $articleRow
     * @param bool $headlineRow
     * @return mixed
     * @todo : Ajouter contrainte magazine
     * @todo : à finir (voir getNextArticle fait par Lahcen)
     */
    static public function getArticleNextRow($articleRow, $headlineRow = false)
    {
        $select = self::getSelectOnlineArticle(null)
             ->filter(array(
                 'published_at__gt' => $articleRow->published_at
             ))
             ->order('published_at ASC');

        return $select->fetchRow();
    }

    /**
     * Returns the $limit last articles
     *
     * @static
     * @param integer $limit
     * @return mixed
     * @throws Centurion_Model_Exception
     * @todo : contrainte par magazine
     */
    public static function getLastArticles($limit)
    {
        if (!is_integer($limit) && $limit == 0) {
            throw new Centurion_Model_Exception('Bad argument type, expected integer');
        }
        return self::getSelectOnlineArticle(null)->limit($limit)->fetchAll();
    }

    /**
     * Returns les last five articles of the magazine for the home newsfeed
     *
     * @static
     * @return mixed
     */
    public static function getLastFiveArticlesForHome()
    {
        return self::getLastArticles(5);
    }

    //-------------------------------------------------------------
    public static function getPreviousArticle($currentArticle)
    {
        //die('entrer dans previous');
        $categoryArticle = $currentArticle->categories->current();
        //Zend_Debug::dump($categoryArticle);die();
        //echo $categoryArticle->slug;die();
        $tablePrevious = Centurion_Db::getSingleton('magazine/article');
        $selectPrevious = $tablePrevious->select(true)
                                        ->setIntegrityCheck(false)
                                        ->join('Magazine_Article_Category', 'category_id = ' . $categoryArticle->id)
                                        ->filter(array(
                                                'is_online' => 1,
                                                'published_at__gt' => $currentArticle->published_at,

                                            )
                                        )
                                        ->order('published_at ASC');
        //echo $selectPrevious;die();
        //$row = $selectPrevious->fetchRow();
        //echo $currentArticle->published_at,'<br/>';
        //echo $row->published_at;die('previous');
        return $selectPrevious->fetchRow();
    }

    public static function getNextArticle($currentArticle)
    {
        //die('entrer dans previous');
        $categoryArticle = $currentArticle->categories->current();
        //Zend_Debug::dump($categoryArticle);die();
        //echo $categoryArticle->slug;die();
        $tableNext = Centurion_Db::getSingleton('magazine/article');
        $selectNext = $tableNext->select(true)
            ->setIntegrityCheck(false)
            ->join('Magazine_Article_Category', 'category_id = ' . $categoryArticle->id)
            ->filter(array(
                'is_online' => 1,
                'published_at__lt' => $currentArticle->published_at,

            )
        )
            ->order('published_at DESC');
        //echo $selectNext;die();
        //$row = $selectNext->fetchRow();
        //echo $currentArticle->published_at,'<br/>';
        //echo $row->published_at;die('next');
        return $selectNext->fetchRow();
    }
    //-------------------------------------------------------------
}