<?php

class Magazine_Model_DbTable_Row_MagazineArticle extends Centurion_Db_Table_Row_Abstract
{
    /**
     * Retrieve the absolute url of the instance.
     * Custom version pour la gestion des articles, dossiers & enfants de dossier
     *
     * @return string
     */
    public function getAbsoluteUrl()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $name = sprintf('%s_%s', $this->getTable()->info(Centurion_Db_Table_Abstract::NAME), 'get');

        // Si nous sommes dans le cas d'un dossier vérifier si c'est un enfant ou un parent pour adapter la route
        if (!empty($this->parent)) {
            // Parent
            if ($this->parent->parent_id == $this->article_id) {
                $name = 'magazine_magazine_article_get_parent';
            }
        } else if (!empty($this->child)) {
            // Enfant
            $name = 'magazine_magazine_article_get_child';
        }

        if (!$router->hasRoute($name)) {
            $data = explode('_', $this->getTable()->info(Centurion_Db_Table_Abstract::NAME), 2);
            return array(array('module' => $data[0], 'controller' => str_replace('_', '-', $data[1]), 'action' => 'get', 'id' => $this->id), 'default');
        }

        $route = $router->getRoute($name);

        if (!($route instanceof Centurion_Controller_Router_Route_Object || $route instanceof Zend_Controller_Router_Route_Chain)) {
            throw new Centurion_Exception(sprintf('%s route is not a Centurion_Controller_Router_Route_Object. Please overload getAbsoluteUrl() function. ', $name));
        }

        return array(array('object' => $this), $name);
    }
}