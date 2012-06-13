<?php

class Magazine_MagazineArticleController extends Centurion_Controller_Action
{
    /**
     * Magazine courant
     *
     * @var null
     */
    protected $_magazineRow = null;

    /*
    * Gestion du multi magazine
    * Récupération du magazine courrant
    * Si il n'existe pas > 404
    */
    public function init()
    {
        $magazineSlug = $this->_getParam('magazine__slug', null);

        $this->_magazineRow = Magazine_Model_Magazine::getMagazineBySlug($magazineSlug);

        $this->forward404If(is_null($this->_magazineRow));
    }

    /**
     * Affichage d'un dossier
     */
    public function getHeadlineAction()
    {
        $parentSlug = $this->_getParam('article__slug', null);
        $this->view->headlineRow = Magazine_Model_MagazineArticle::getHeadlineBySlug($this->_magazineRow->id, $parentSlug);

        $this->forward404If(is_null($this->view->headlineRow));

        $this->view->magazineRow = $this->_magazineRow;
        $this->view->childRowset = Magazine_Model_MagazineArticle::getHeadlineChilds($this->_magazineRow->id, $this->view->headlineRow->article);
    }

    /**
     * Récuperation de la row (article/dossier) en fonction du slug & du magazine
     *
     * @param [get]string $article__slug
     * @return Magazine_Model_DbTable_Row_MagazineArticle
     * @todo : Gestion du type d'article
     */
    public function getArticleAction()
    {
        $this->view->magazineArticleRow = $this->_get();;

        // Récupération & assignation à la vue de l'article & du magazine depuis l'objet magazine/article
        $this->view->articleRow = $this->view->magazineArticleRow->article;
        $this->view->magazineRow = $this->view->magazineArticleRow->magazine;

        //echo $this->view->articleRow->published_at;die();

        $this->_getRelatedInformation($this->view->articleRow);

        $this->view->previousMagazineArticleRow = Magazine_Model_MagazineArticle::getPreviousArticle($this->view->magazineArticleRow);
        $this->view->nextMagazineArticleRow = Magazine_Model_MagazineArticle::getNextArticle($this->view->magazineArticleRow);

        if (0 == $this->view->magazineArticleRow->article->is_article) {
            $this->render('get-interview');
        }
    }

    protected function _get()
    {
        $articleSlug = $this->_getParam('article__slug', null);
        $magazineArticleRow = Magazine_Model_MagazineArticle::getMagazineArticleBySlug($this->_magazineRow->id, $articleSlug);

        $this->forward404If(is_null($magazineArticleRow));

        // Incrémentation du nombre de vue de l'article
        $magazineArticleRow->article->view = $magazineArticleRow->article->view + 1;
        $magazineArticleRow->article->save();

        // Traitement du body de l'objet pour y insérer des widgets
        Centurion_Signal::factory('pre_display_rte')->send($this, array($magazineArticleRow->article));

        // Bloc 3 derniers articles
        $this->view->lastArticleRowset = Magazine_Model_MagazineArticle::getAllMagazineArticle(1, false, true, 3);

        return $magazineArticleRow;
    }

    /**
     * Get all informations about an article
     *  - tags
     *  - related events
     *  - related articles
     *  - related appellations
     *  - next/prev article
     *  - headline informations
     *
     * @param $magazineArticleRow
     *
     */
    protected function _getRelatedInformation($articleRow)
    {
        // Informations supplémentaire pour un article appartenant à un dossier
        if(!empty($this->view->magazineArticleRow->child)) {
            $this->view->isChild = true;
            $this->view->headlineRow = Magazine_Model_MagazineArticle::getHeadlineBySlug($this->_magazineRow->id, $this->_getParam('child__parent__article__slug', null));
            $this->view->childRowset = Magazine_Model_MagazineArticle::getHeadlineChilds($this->_magazineRow->id, $this->view->headlineRow->article_id, false, true);
        }

        // Tag
        $this->view->tagRowset = Magazine_Model_Tag::getTagsOf($articleRow);

        // Related articles
        $this->view->relatedArticleRowset = Magazine_Model_MagazineArticle::getRelatedArticleByTags($this->_magazineRow->id, $articleRow);
    }
}