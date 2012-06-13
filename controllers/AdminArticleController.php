<?php

class Magazine_AdminArticleController extends Centurion_Controller_CRUD
{

    public function init()
    {
        $this->_helper->authCheck();
        $this->_helper->aclCheck();

        /* KEEP CONTEXT ON CURRENT MAGAZINEROW */
        $magazineId = $this->_getParam('magazine', null);
        if (null === $magazineId) {
            $this->_redirect($this->view->url(array('module' => 'magazine', 'controller' => 'admin-magazine', 'action' => 'index'), 'default'));
        }
        $magazineRow = Centurion_Db::getSingleton('magazine/magazine')->findOneById($magazineId);
        $this->_extraParam['magazine'] = $magazineRow->id;
        

        /* Form associé */
        $this->_formClassName = 'Magazine_Form_Model_Article';

        /* Champs sur une page de liste */
        $this->_displays = array(
            'title'              =>  array(
                                        'type' => Centurion_Controller_CRUD::COL_TYPE_FIRSTCOL,
                                        'param' => array('cover' => 'cover', 'title' => 'title', 'subtitle' => 'subtitle'),
                                        'label' => $this->view->translate('Title')
                                        ), 
            'published_at'       =>  array('label' => $this->view->translate('Publication date'),
                                           'filters' => self::COL_DISPLAY_DATE),
            //'author'             =>  $this->view->translate('Author'),
            'is_online'          =>  array('label' => $this->view->translate('Online'),
                                           'type'  => Centurion_Controller_CRUD::COL_TYPE_ONOFF),
            'is_contributed'          =>  array('label' => $this->view->translate('Contributed'),
                                           'type'  => Centurion_Controller_CRUD::COL_TYPE_ONOFF),
        );

        $this->_filters = array (
            'title'       => $this->view->translate('Title'),
            'is_online'       => array(
                'type'  => self::FILTER_TYPE_SELECT,
                'data'  => array(
                    '' => NULL,
                    '0' => $this->view->translate('Non'),
                    '1' => $this->view->translate('Oui')
                ),
                'label' => $this->view->translate('Is online ?')
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
        
        $this->view->placeholder('headling_1_content')->set($this->view->translate('Magazine %s : Manage article', $magazineRow->title));
        $this->view->placeholder('headling_1_add_button')->set($this->view->translate('article'));

        parent::init();
    }

    public function jsonAutocompleteAction()
    {
        $query = $this->_request->getParam('term', null);

        if (null === $query) {
            $this->_helper->json(array());
        }

        // aggressive matching
        $query = preg_replace('/./', '$0%', $query);

        // regular matching
        $query = '%'.str_replace(' ', '%', $query).'%';

        $articleRowset = Centurion_Db::getSingleton('magazine/article')->select(false)
            ->from('magazine_article', array('id', 'label' => 'title'))
            ->filter(array('title__contains' => $query,))
            ->order('title')->fetchAll();

        $this->_helper->json($articleRowset->toArray());
    }
}
