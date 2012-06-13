<?php

class Magazine_AdminMagazineController extends Centurion_Controller_CRUD
{

    public function init()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();

        /* Form associé */
        $this->_formClassName = 'Magazine_Form_Model_Magazine';

        /* Champs sur une page de liste */
        $this->_displays = array(
            'title'              =>  array(
                'type' => Centurion_Controller_CRUD::COL_TYPE_FIRSTCOL,
                'param' => array('cover' => 'logo', 'title' => 'title', 'subtitle' => 'subtitle'),
                'label' => $this->view->translate('Title')
            ),
            'addActions'         => array(
                'label' => $this->view->translate('Articles'),
                'sortable' => false,
                'type'  => self::COLS_CALLBACK,
            ),
        );

        $this->view->placeholder('headling_1_content')->set($this->view->translate('Manage magazine'));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('magazine'));

        parent::init();
    }


    /**
     * Display content of column 'Articles' in the grid
     *
     * @return string
     */
    public function addActions($row)
    {
        $url['url'] = $this->view->url(array(
            'module' => $this->getRequest()->getModuleName(),
            'controller' => 'admin-article',
            'action' => 'index',
            'magazine' => $row->id),
            'default',
            true
        );
        $url['label'] = $this->view->translate('Edit');

        return sprintf('<a href="%s">%s</a>', $url['url'], $url['label']);
    }

}