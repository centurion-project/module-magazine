<?php

/**
 * @todo : gestion multi magazine
 */
class Magazine_TagController extends Centurion_Controller_Action
{
    protected $_magazineRow = 1;

    public function getAction()
    {
        $tagSlug = $this->_getParam('slug', null);

        $this->view->tagRow = Magazine_Model_Tag::getTagBySlug($tagSlug);

        $this->forward404If(is_null($this->view->tagRow));

        //$articleRowset = $this->view->tagRow->articles;
        $articleRowset = Magazine_Model_MagazineArticle::getAllArticleByTag($this->_magazineRow, $this->view->tagRow->id, 20);

        //Article en avant
        $this->view->articleTopRow = $this->_rowsetSlice($articleRowset, 0, 1);

        //Liste des articles
        $this->view->articleListRowset   = $this->_rowsetSlice($articleRowset, 1, 5 );

        //Bloc dossier
        $this->view->lastHeadlineRow = Magazine_Model_MagazineArticle::getLastHeadline($this->_magazineRow);

        $this->view->magazineRow = Centurion_Db::getSingleton('magazine/magazine')->fetchRow();

        //Articles les plus consultés
        $this->view->articleMostViewedRowset = Magazine_Model_MagazineArticle::getAllArticleByTag($this->_magazineRow, $this->view->tagRow->id, 6);

        //Bloc auteurs
        $this->view->authorRowset = Magazine_Model_Author::getLastAuthor($this->_magazineRow, 3);
    }

    /**
     * Fake array_slice pour les rowset
     *
     * @param $rowset
     * @param $start
     * @param $nb
     * @return array|row
     */
    protected function _rowsetSlice($rowset, $offset, $length)
    {
        $data = array();
        for ($i = $offset; $i < $offset + $length; $i ++) {
            if (isset($rowset[$i])) {
                $data[] = $rowset[$i];
            }
        }

        // Si il n'y a qu'un élement, retourne une row
        if (1 == count($data)) {
            return $data[0];
        }

        return $data;
    }
}