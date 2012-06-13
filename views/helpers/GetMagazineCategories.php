<?php

class Magazine_View_Helper_GetBlogCategories extends Zend_View_Helper_Abstract
{
    public function getMagazineCategories()
    {
    	$categoriesTable = Centurion_Db::getSingleton('magazine/category');

		$categories = $categoriesTable->fetchAll();
        
        return $categories;
    }
}