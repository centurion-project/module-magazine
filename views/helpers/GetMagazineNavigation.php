<?php

/**
 * @todo : factoriser conditions dans l'helper
 * @todo : test unitaire
 */
class Magazine_View_Helper_GetMagazineNavigation extends Zend_View_Helper_Abstract
{
    /**
     * Navigation data
     *
     * @var array
     */
    protected $_navigation = array();

    /**
     * Retourne les données qui compose la navigation du magazine courant
     *
     * @param Magazine_Model_DbTable_Row_Magazine $magazineRow
     * @return array
     */
    public function getMagazineNavigation(Magazine_Model_DbTable_Row_Magazine $magazineRow)
    {
        // Catégories actives
        $categoryRowset = Centurion_Db::getSingleton('magazine/category')->fetchAll();
        foreach ($categoryRowset as $categoryRow) {
            $this->_navigation[] = array(
                'url'  => $this->view->url(array('object' => $categoryRow), 'magazine_category_get'),
                'name' => $categoryRow->name
            );
        }

        // Dernier dossier
        $headlineRow = Magazine_Model_MagazineArticle::getLastHeadline($magazineRow);

        if (null !== $headlineRow) {
            $this->_navigation[] = array(
                'url'  => $headlineRow->permalink,
                'name' => $headlineRow->article->title
            );
        }

        // Agenda
        // Désactivé : fix 6746
        /*$this->_navigation[] = array(
            'url'  => $this->view->url(array(), 'ir_evenement_list'),
            'name' => $this->view->translate('Agenda')
        );*/

        // Médiathèque
        $this->_navigation[] = array(
            'url'       => $this->view->url(array(), 'ir_mediatheque_list'),
            'name'      => $this->view->translate('Médiathèque'),
        );

        return $this->_navigation;
    }
}
