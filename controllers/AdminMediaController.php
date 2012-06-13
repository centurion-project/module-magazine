<?php

class Magazine_AdminMediaController extends Centurion_Controller_CRUD
{

    public function init()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();

        /* Form associé */
        $this->_formClassName = 'Magazine_Form_Model_Media';

        /* Champs sur une page de liste */
        $this->_displays = array(
            'title'              =>  array(
                                        'type' => Centurion_Controller_CRUD::COL_TYPE_FIRSTCOL,
                                        'param' => array('cover' => 'media', 'title' => 'title', 'subtitle' => null),
                                        'label' => $this->view->translate('Title')
                                        ),
            'is_valid'       => array(
                'type'  => self::COL_TYPE_ONOFF,
                'label' => $this->view->translate('Is valid ?')
            ),
            'is_contributed'       => array(
                'type'  => self::COL_TYPE_ONOFF,
                'label' => $this->view->translate('Is contributed ?')
            ),
        );

        $this->_filters = array (
            'title'       => $this->view->translate('Title'),
            'is_valid'       => array(
                'type'  => self::FILTER_TYPE_SELECT,
                'data'  => array(
                    '' => NULL,
                    '0' => $this->view->translate('Non'),
                    '1' => $this->view->translate('Oui')
                ),
                'label' => $this->view->translate('Is valid ?')
            ),
            'is_contributed'       => array(
                'type'  => self::FILTER_TYPE_SELECT,
                'data'  => array(
                    '' => NULL,
                    '0' => $this->view->translate('Non'),
                    '1' => $this->view->translate('Oui')
                ),
                'label' => $this->view->translate('Is contributed ?')
            ),
        );

        $this->view->placeholder('headling_1_content')->set($this->view->translate('Manage media'));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('media'));

        //Setting the id if needed (if we come from the ir/admin-media, we need to)
        $id = $this->_getParam('id');
        if (null != $id && strlen($id) > 5) {
            try {
                $rowId = Ir_Model_Media::getIdOfMagazineMediaFromMediaFileId($id);
                $this->_setParam('id', $rowId);
            } catch(Centurion_Application_Exception $e) {
                //nothing to do
                $this->_redirect($this->view->url(
                    array(
                        'module' => 'ir',
                        'controller' => 'admin-media-ir',
                        'action' => 'get',
                        'id' => $id,
                        '_next' => urlencode($this->_getParam('_next')),
                    )));
            }
        }

        parent::init();
    }
}