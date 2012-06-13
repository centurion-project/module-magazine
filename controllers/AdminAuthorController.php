<?php

class Magazine_AdminAuthorController extends Centurion_Controller_CRUD
{

    public function init()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();

        /* Form associé */
        $this->_formClassName = 'Magazine_Form_Model_Author';

        /* Champs sur une page de liste */
        $this->_displays = array(
            'title'              =>  array(
                                        'type' => Centurion_Controller_CRUD::COL_TYPE_FIRSTCOL,
                                        'param' => array('cover' => 'picture', 'title' => 'lastname', 'subtitle' => 'firstname'),
                                        'label' => $this->view->translate('Title')
                                        ),
        );

        $this->view->placeholder('headling_1_content')->set($this->view->translate('Manage author'));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('author'));

        parent::init();
    }
}