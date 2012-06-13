<?php

class Magazine_AdminTagController extends Centurion_Controller_CRUD
{

    public function init()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
    
        /* Form associé */
        $this->_formClassName = 'Magazine_Form_Model_Tag';

        /* Champs sur une page de liste */
        $this->_displays = array(
            'name'              =>  $this->view->translate('Name'),
        );
        
        $this->view->placeholder('headling_1_content')->set($this->view->translate('Manage tag'));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('tag'));

        parent::init();
    }
}