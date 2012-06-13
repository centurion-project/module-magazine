<?php

class Magazine_AdminCategoryController extends Centurion_Controller_CRUD
{

    public function init()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();
    
        /* Form associé */
        $this->_formClassName = 'Magazine_Form_Model_Category';

        /* Champs sur une page de liste */
        $this->_displays = array(
            'name'              =>  $this->view->translate('Name'),
        );
        
        $this->view->placeholder('headling_1_content')->set($this->view->translate('Manage category'));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('category'));

        parent::init();
    }
}