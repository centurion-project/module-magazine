<?php

/**
 * @todo : gestion multi magazine
 */
class Magazine_CategoryController extends Centurion_Controller_Action
{
    protected $_magazineRow = 1;

    public function getAction()
    {
        // TODO : Gestion multi magazines
        $this->_magazineRow = Centurion_Db::getSingleton('magazine/magazine')->fetchRow();

        $catyegorySlug = $this->_getParam('slug', null);

        if ($catyegorySlug == 'evenement') {
            $this->view->eventRowset = Ir_Model_Evenement::getNextEventsForPress(5);
        }

        $this->view->categoryRow = Magazine_Model_Category::getCategoryBySlug($catyegorySlug);

        $this->forward404If(is_null($this->view->categoryRow));

        //Informations pour le header
        $this->view->magazineRow = $this->_magazineRow;

        //Bloc dossier
        $this->view->lastHeadlineRow = Magazine_Model_MagazineArticle::getLastHeadline($this->_magazineRow);

        //Bloc les articles les plus lus
        // TODO : Only is_promoted
        $this->view->promotedMagazineArticleRowset = Magazine_Model_MagazineArticle::getAllArticleByCategorySlug($this->_magazineRow, $this->view->categoryRow->slug, 10);
        $this->view->fiveMagazineArticleRowset = Magazine_Model_MagazineArticle::getAllMagazineArticle($this->_magazineRow, false, true, 5);

        // TODO : Exlure les is_promoted
        //$this->view->magazineArticleRowset = Magazine_Model_MagazineArticle::getAllArticleByCategorySlug($this->_magazineRow, $this->view->categoryRow->slug, 6);
    }
}