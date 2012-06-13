<?php

class Magazine_MagazineController extends Centurion_Controller_Action
{
    public function indexAction()
    {
        $magazineSlug = $this->_getParam('slug', null);
        $this->view->magazineRow = Magazine_Model_Magazine::getMagazineBySlug($magazineSlug);

        $this->forward404If(is_null($this->view->magazineRow));

        $magazineArticleRowset = Magazine_Model_MagazineArticle::getAllMagazineArticle($this->view->magazineRow, false, false, 9);

        //TODO : Placer la découpe du rowset dans la vue
        $this->view->articleSliderRowset = $this->_rowsetSlice($magazineArticleRowset, 0, 3);
        $this->view->articleLastRowset   = $this->_rowsetSlice($magazineArticleRowset, 3, 2);
        $this->view->articleNewsRowset   = $this->_rowsetSlice($magazineArticleRowset, 5, 4);

        // Récupère le dernier dossier mis en ligne (published_at & non created_at)
        $this->view->lastHeadlineRow = Magazine_Model_MagazineArticle::getLastHeadline($this->view->magazineRow);

        // Bloc 'Ils parlent de la région'
        $this->view->bloggerRowset      = User_Model_Profile::getBlogueurs(null, true, 3);
        $this->view->ambassadorRowset   = User_Model_Profile::getAmbassadors(null, true, 3);
        $this->view->winemakerRowset    = User_Model_Profile::getVignerons(null, true, 3);

        // Bloc citation
        $this->view->verbatimRow = Ir_Model_Verbatim::getRandomVerbatim();

        // Bloc mediathèque
        $this->view->mediaRow    = Magazine_Model_Media::getRandomMedia();
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