<?php

/**
 * Mapper class for the table magazine_magazine_article
 *
 * Une entré dans cette table correspond à un article car il n'existe pas en dehors du contexte d'un magazine.
 */
class Magazine_Model_MagazineArticle
{
    /**
     * Vérifie la validité d'un ID
     *
     * Si on lui passe une row, il renvoie l'id
     * Si l'id n'est pas un numérique, une exception est lévée
     *
     * @static
     * @param $id
     * @return mixed
     * @throws Centurion_Model_Exception
     */
    public static function getIdCleanOf($id)
    {
        // Si une row est passé en argument au lieu de l'id, récupérer l'id de la row
        if ($id instanceof Zend_Db_Table_Row_Abstract) {
            $id = $id->pk;

            if (is_array($id)) {
                throw new Centurion_Model_Exception('Bad argument type, array given instead of numeric');
            }
        }

        //Si l'arguement passé n'est pas un nombre, lever une exception Centurion_Model_Exception
        if (!is_numeric($id)) {
            throw new Centurion_Model_Exception('Bad argument type');
        }

        return $id;
    }

    /**
     * @static
     * @param $slug
     */
    public static function getSlugCleanOf($slug)
    {
        // Si la row est passé en argument au lieu du slug, récupérer le slug de la row
        if ($slug instanceof Zend_Db_Table_Row_Abstract) {
            $slug = $slug->slug;
        }

        return $slug;
    }

    /**
     * Genere un objet Zend_Db_Select qui permet de récupérer les articles en ligne dans un magazine.
     *
     * @static
     * @param int|string|Zend_Db_Table_Row_Abstract $magazineId
     * @param bool $fetchHeadline - Retourne les dossiers en plus des articles
     * @param bool $fetchHeadlineChilds - Retourne les enfants des dossiers
     * @return Zend_Db_Select
     */
    public static function getSelectOnlineArticle($magazineId, $fetchHeadline = false, $fetchHeadlineChilds = false, $limit = null, $orderByView = false)
    {
        $magazineId = self::getIdCleanOf($magazineId);

        // Selectionne tous les articles, dossier et articles appartenant à un dossier actuellement en ligne
        $select = Centurion_Db::getSingleton('magazine/magazine_article')
            ->select(true)
            ->filter(array(
                // Réstriction lié à la date
                'article__is_online'        => 1,
                '!article__published_at'    => '0000-00-00 00:00:00',
                'article__published_at__lt' => new Zend_Db_Expr('NOW()'),
                //'article__published_at__lt' => date('Y-m-d H:i:s'),
                // Existe dans un magazine
                'magazine_id'               => $magazineId,
            ));

        // Exclue les dossiers
        if (false == $fetchHeadline) {
            $select->filter(array('article__is_headline' => '0'));
        }

        // Exclue les articles appartenant à au moins un dossier
        if (false == $fetchHeadlineChilds) {
            // Récupère les articles à exclure
            $excludeRowset = Centurion_Db::getSingleton('magazine/article_article')
                ->select(true)
                ->reset(Zend_Db_Table_Select::COLUMNS)
                ->columns('child_id')
                ->where('magazine_id = ?', $magazineId)
                ->fetchAll();

            // Si il y a des articles à exclure, ajout d'un filtre avec les id des articles concernés
            if (0 < $excludeRowset->count()) {
                // Construction d'un tableau contenant les id à exclure
                $excludeIdArray = array();
                foreach ($excludeRowset as $excludeRow) {
                    $excludeIdArray[] = $excludeRow->child_id;
                }

                // Ajouter les id au select
                $select->filter(array('!article_id__in' => $excludeIdArray));
            }
        }

        // Limit
        if (null !== $limit) {
            $select->limit($limit);
        }

        // Order
        if (false !== $orderByView) {
            $select->order('view DESC');
        }

        return $select;
    }

    /**
     * Récupère un article en fonction du slug
     *
     * @static
     * @param $magazineId
     * @param $slug
     * @return null|Magazine_Model_DbTable_Row_MagazineArticle
     */
    public static function getMagazineArticleBySlug($magazineId, $slug)
    {
        $select = self::getSelectOnlineArticle($magazineId, true, true);

        $select->setIntegrityCheck(false);

        $select->filter(array('article__slug' => $slug));

        return $select->fetchRow();
    }

    /**
     * Récupére tous les article
     *
     * @static
     * @param $magazineRow
     * @param bool $fetchHeadlineChilds
     * @param bool $fetchHeadline
     * @param null $limit
     * @param bool $orderByView
     * @return mixed
     * @todo : ajouter la contrainte pas magazine pour l'exclusion des dossiers
     */
    public static function getAllMagazineArticle($magazineId, $fetchHeadline = false, $fetchHeadlineChilds = false, $limit = null, $orderByView = false)
    {
        $select = self::getSelectOnlineArticle($magazineId, $fetchHeadline, $fetchHeadline, $limit, $orderByView);

        if (false == $orderByView) {
            $select->order('article__published_at DESC');
        }

        return $select->fetchAll();
    }

    /**
     * Get an headline
     *
     * @static
     * @param $magazineId
     * @param $slug
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public static function getHeadlineBySlug($magazineId, $slug)
    {
        $select = self::getSelectOnlineArticle($magazineId, true);

        $select->filter(array(
            'article__slug' => $slug
        ));

        $select->setIntegrityCheck(false);
        $select->join('magazine_article_article', 'article_id = parent_id');

        return $select->fetchRow();
    }

    /**
     * Get all childs of an headline
     *
     * @static
     * @param $magazineId
     * @param $parentId
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public static function getHeadlineChilds($magazineId, $parentId)
    {
        $parentId = self::getIdCleanOf($parentId);
        $select = self::getSelectOnlineArticle($magazineId, false, true);

        $select->setIntegrityCheck(false);
        $select->join('magazine_article_article', 'article_id = child_id AND parent_id = ' . $parentId);

        $select->order('order DESC');

        return $select->fetchAll();
    }

    /**
     * Retourne le dernier dossier en fonction de la date de publication
     *
     * @static
     * @param $magazineRow
     * @return mixed
     */
    public static function getLastHeadline($magazineId)
    {
        $select = self::getSelectOnlineArticle($magazineId, true);

        $select->setIntegrityCheck(false);
        $select->join('magazine_article_article', 'article_id = parent_id');

        $select->order('article__published_at DESC');

        return $select->fetchRow();
    }

    /**
     * Génére le select à ajouter à la fonction getNextBy ou getPreviousBy
     * Permet d'utiliser permalink et d'avoir le switch automatique entre un article et un article appartenant à un dossier
     *
     * @static
     * @param $magazineId
     * @return Zend_Db_Select
     */
    public static function getSelectForGetNextByOrGetPrevByFunction($magazineId)
    {
        $select = Magazine_Model_MagazineArticle::getSelectOnlineArticle($magazineId);

        return $select;
    }

    /**
     * Retourne tous les articles liés à un tag
     *
     * @static
     * @param $magazineId
     * @param bool $fetchHeadline
     * @param bool $fetchHeadlineChilds
     * @param null $limit
     * @param bool $orderByView
     * @return mixed
     */
    public static function getAllArticleByTag($magazineId, $tagId, $limit = null, $orderByView = false)
    {
        $tagId = self::getIdCleanOf($tagId);
        $select = self::getSelectOnlineArticle($magazineId, false, true, $limit, $orderByView);

        $select->setIntegrityCheck(false);
        $select->join('magazine_tag_proxy', 'proxy_model = content_type_id AND proxy_pk = article_id AND tag_id = ' . $tagId);

        return $select->fetchAll();
    }

    /**
     * Retourne les articles contribués par un user
     *
     * @static
     * @param integer $magazineId
     * @param User_Model_DbTable_Row_Profile $userRow
     * @return Zend_Db_Select
     * @throws Centurion_Model_Exception
     */
    public static function getContributedArticleByUser($magazineId, $userRow)
    {
        if (null != $userRow && $userRow instanceof User_Model_DbTable_Row_Profile) {
            $select = self::getSelectOnlineArticle($magazineId);
            $select->filter(array('article__author__user_id' => $userRow->user__id))
                    ->order('article__published_at DESC');
            return $select;
        } else {
            throw new Centurion_Model_Exception('Bad argument type, expected instance of User_Model_DbTable_Profile, get '.gettype($userRow));
        }
    }

    /**
     * Récupère les articles qui ont le plus de tags en commun avec l'objet donné
     *
     * @static
     * @param $rowId
     * @param $modelId
     * @return null
     *
     * @todo : filter par magazine
     * @todo : voir pourquoi les filters déconnent
     */
    public static function getRelatedArticleByTags($magazineId, $row)
    {
        // Vérifier que l'objet a bien une n:m avec les tags et si oui, qu'elle a bien au moins un tag
        if (!isset($row->tags) || count($row->tags) <= 0) {
            return null;
        }

        // Création d'un tableau contenant les id des tags de l'objet pour la requête
        $tagIdArray = array();
        foreach ($row->tags as $tag) {
            $tagIdArray[] = $tag->id;
        }

        $articleTableName = get_class(Centurion_Db::getSingleton('magazine/article'));
        $articleTableContentTypeId = Centurion_Db::getSingleton('core/content_type')->getContentTypeIdOf($articleTableName);

        // Récupération des articles
        $select = self::getSelectOnlineArticle($magazineId, false, $fetchHeadlineChilds = false, 3);

        $select->setIntegrityCheck(false)
            ->join('magazine_tag_proxy', 'article_id = proxy_pk')
            ->columns(new Zend_Db_Expr('COUNT(*) AS poid'))
            ->filter(array(
                //'tag_id__in' => $tagIdArray,
                //'proxy_model' => $articleTableContentTypeId,
            ))
            ->where(new Zend_Db_Expr('tag_id IN (' . implode(',', $tagIdArray) . ')'))
            ->where('proxy_model = ?', $articleTableContentTypeId)
            ->group('article_id')
            ->order('poid DESC');

        // Si l'objet est un article l'exclure des résultats
        if (get_class($row->getTable()) == $articleTableName) {
            //$select->filter(array('!proxy_pk' => $row->id));
            $select->where('article_id <> ?', $row->id);
        }

        return $select->fetchAll();
    }

    /**
     * Récupère tous les articles en ligne liées à une catégorie
     *
     * @static
     * @param $magazineId
     * @param $slug
     * @param $limit
     * @return mixed
     */
    public static function getAllArticleByCategorySlug($magazineId, $slug, $limit)
    {
        $slug = self::getSlugCleanOf($slug);
        $select = self::getSelectOnlineArticle($magazineId, false, true, $limit);

        $select->setIntegrityCheck(false)
            ->join('magazine_article_category', 'magazine_article_category.article_id = id', '')
            ->join('magazine_category', 'category_id = magazine_category.id', '')
            ->where('magazine_category.slug = ?', $slug)
            ->order('published_at DESC');

        return $select->fetchAll();
    }


    /**
     * @static
     * @param $magazineArticleRow
     * @return mixed
     * @todo : Cleaner
     */
    public static function getPreviousArticle($magazineArticleRow)
    {
        //die('entrer dans previous magazine');
        $currentArticleRow = $magazineArticleRow->article;
        $categoryArticleRow = $currentArticleRow->categories->current();
        //Zend_Debug::dump($categoryArticle);die();
        //echo $categoryArticle->slug;die();

//        $excludeArticleRowset = Centurion_Db::getSingleton('magazine/article')->select(true)
//            ->reset(Zend_Db_Table_Select::COLUMNS)
//            ->columns('id')
//            ->join('magazine_article_category', 'magazine_article.id = magazine_article_category.article_id', '')
//            ->where('magazine_article_category.category_id != ' . $categoryArticleRow->id)
//            ->fetchAll()
//        ;


//        foreach ($excludeArticleRowset as $excludeRow) {
//            $excludeArticleIdArray[] = $excludeRow->id;
//        }

//        Zend_Debug::dump($excludeArticleIdArray);die();

        $tablePrevious = Centurion_Db::getSingleton('magazine/magazine_article');
        $selectPrevious = $tablePrevious->select(true)
            ->setIntegrityCheck(false)
            //->join('Magazine_Article_Category', 'category_id = ' . $categoryArticleRow->id)
            ->filter(array(
                'article__is_online' => 1,
                'magazine_id' => $magazineArticleRow->magazine_id,
                //'article_categories__category_id' => $categoryArticleRow->id,
                'article__published_at__gt' => $currentArticleRow->published_at,
//                '!article_id__in' => $excludeArticleIdArray

            )
        )
            ->order('published_at ASC');



//        echo $selectPrevious;die();
        //$row = $selectPrevious->fetchRow();
        //echo $currentArticle->published_at,'<br/>';
        //echo $row->published_at;die('previous');

        $previousMagazineArticleRow = $selectPrevious->fetchRow();

        //$previousMagazineArticleRow = 1;
        return $previousMagazineArticleRow;
    }

    /**
     * @static
     * @param $magazineArticleRow
     * @return mixed
     * @todo : Cleaner
     */
    public static function getNextArticle($magazineArticleRow)
    {
        //die('entrer dans previous magazine');
        $currentArticleRow = $magazineArticleRow->article;
        $categoryArticleRow = $currentArticleRow->categories->current();
        //Zend_Debug::dump($categoryArticle);die();
        //echo $categoryArticle->slug;die();

//        $excludeArticleRowset = Centurion_Db::getSingleton('magazine/article')->select(true)
//            ->reset(Zend_Db_Table_Select::COLUMNS)
//            ->columns('id')
//            ->join('magazine_article_category', 'magazine_article.id = magazine_article_category.article_id', '')
//            ->where('magazine_article_category.category_id != ' . $categoryArticleRow->id)
//            ->fetchAll()
//        ;


//        foreach ($excludeArticleRowset as $excludeRow) {
//            $excludeArticleIdArray[] = $excludeRow->id;
//        }

//        Zend_Debug::dump($excludeArticleIdArray);die();

        $tableNext = Centurion_Db::getSingleton('magazine/magazine_article');
        $selectNext = $tableNext->select(true)
            ->setIntegrityCheck(false)
        //->join('Magazine_Article_Category', 'category_id = ' . $categoryArticleRow->id)
            ->filter(array(
                'article__is_online' => 1,
                'magazine_id' => $magazineArticleRow->magazine_id,
                //'article_categories__category_id' => $categoryArticleRow->id,
                'article__published_at__lt' => $currentArticleRow->published_at,
//                '!article_id__in' => $excludeArticleIdArray,
                'article__is_headline' => 0

            )
        )
            ->order('published_at DESC');



//        echo $selectNext;die();
        //$row = $selectPrevious->fetchRow();
        //echo $currentArticle->published_at,'<br/>';
        //echo $row->published_at;die('previous');

//        $rows = $selectNext->fetchAll();
//        foreach ($rows as $row)
//            echo $row->article__title . '<br/>';
//        die();


        $nextMagazineArticleRow = $selectNext->fetchRow();

        //$previousMagazineArticleRow = 1;
        return $nextMagazineArticleRow;
    }

    //------------------------------------------------------------------------------------------------------------------

    /**
     * @static
     * @param $magazineId
     * @param $object
     * @param $limit
     * @todo : voir si il est possible de factoriser avec Ir_Model_Evenement::getNextEvenementByObject
     * @todo : contrainte multi magazine
     */
    public static function getRelatedArticleByObject($magazineId, $object, $limit = null)
    {
        if (!isset($object->articles)
            || empty($object->articles)
            || !count($object->articles) > 0) {
            return null;
        }

        if (is_array($object->pk)) {
            throw new Centurion_Model_Exception('Bad argument type, expected string/int, get array');
        }

        /*$select = $object->getTable()
            ->select(true)
            ->setIntegrityCheck(false)
            ->reset(Zend_Db_Select::COLUMNS)
            ->join('magazine_article_content', 'id = magazine_article_content.proxy_pk AND content_type_id = magazine_article_content.proxy_model')
            ->join('magazine_magazine_article', 'magazine_magazine_article.article_id = magazine_article_content.article_id AND magazine_magazine_article.magazine_id = ' . $magazineId)
            ->join('magazine_article', 'magazine_article.id = magazine_magazine_article.article_id')
            ->filter(array(
                // Ne pas utiliser filter pour la date : ne fonctionne pas avec les proxy_pk & proxy_model
                //'evenements__start_at' => date('Y/m/d')
                'id'                => $object->pk,
                'is_online'         => 1,
            ))
            ->where('published_at <> "0000-00-00 00:00:00"')
            ->where('published_at < NOW()')
            ->order('published_at DESC')
            ->limit($limit);*/

        $articleId = array();
        foreach ($object->articles as $articleRow) {
            $articleId[] = $articleRow->id;
        }

        $select = Centurion_Db::getSingleton('magazine/magazine_article')
            ->select(true)
            ->filter(array(
                'magazine_id' => $magazineId,
                'article_id__in' => $articleId,
                // Réstriction lié à la date
                'article__is_online'        => 1,
                '!article__published_at'    => '0000-00-00 00:00:00',
                'article__published_at__lt' => new Zend_Db_Expr('NOW()'),
                //'article__published_at__lt' => date('Y-m-d H:i:s'),
                // Existe dans un magazine
                'magazine_id'               => $magazineId,
            ))
            ->order('article__published_at DESC')
            ->limit($limit);

        return $select->fetchAll();
    }
}