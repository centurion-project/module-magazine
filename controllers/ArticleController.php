<?php
class Magazine_ArticleController extends Centurion_Controller_Action
{
    protected $_magazineRow = null;

    public function init()
    {
        /*
         * Gestion du multi magazine
         * Récupération du magazine courrant
         * Si il n'existe pas > 404
         */
        $magazineSlug = $this->_getParam('magazine-slug', null);

        $this->_magazineRow = Magazine_Model_Magazine::getMagazineBySlug($magazineSlug);

        $this->forward404If(is_null($this->_magazineRow));
    }

    /**
     * Listing des articles par tag
     */
    public function tagAction()
    {
        $tagSlug = $this->_getParam('slug', null);
        $this->view->tagRow = Magazine_Model_Tag::getTagBySlug($tagSlug);

        $this->forward404If(is_null($this->view->tagRow));

        $articleRowset = $this->view->tagRow->articles;
        //Article en avant
        $this->view->articleTopRow = $this->_rowsetSlice($articleRowset, 0, 1);
        //List des articles
        $this->view->articleListRowset   = $this->_rowsetSlice($articleRowset, 1, 5);

        //Bloc dossier
        $this->view->lastHeadlineRow = Magazine_Model_Article::getLastHeadline($this->_magazineRow);

        //Bloc evenement
        //TODO : A modifier en fonction des retours de Thomas
        $this->view->evenementBlocRow = $this->view->tagRow->evenements[0];

        //Articles les plus consultés
        $this->view->articleMostViewedRowset = Magazine_Model_Article::getAllArticle($this->_magazineRow, false, 3, true);


        //Blog bottom evenement
        $this->view->evenementRowset = Ir_Model_Evenement::getAllEvenement(5);
    }
}
