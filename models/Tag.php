<?php

class Magazine_Model_Tag
{

    public static function getTagsOf($row)
    {
        $proxyModel = Centurion_Db::getSingleton('core/contentType')->getContentTypeIdOf($row);
        $proxyPk = $row->id;
        
        $select = Centurion_Db::getSingleton('magazine/tag')->select(true)
            //->joinLeft('magazine_tag_proxy', 'tag_id = magazine_tag.id', null)
            ->join('magazine_tag_proxy', 'proxy_pk = ' . $proxyPk . ' and proxy_model = ' . $proxyModel . ' and tag_id = magazine_tag.id', null)
            ->where('proxy_model = ' . $proxyModel)
            ->where('proxy_pk = ' . $proxyPk);


            return $select->fetchAll();
    }
    
    /**
     * @todo: optimize it
     */
    public static function getTagsList()
    {
        $tagTable = Centurion_Db::getSingleton('magazine/tag');
        
        return $tagTable->select(true)->fetchAll();
    }
    
    public static function getTagsListForMultiOptions()
    {
    	$return = array();
    	
    	foreach (self::getTagsList() as $row) {
    		$return[$row->id] = $row->name;
    	}
    	
    	return $return;
    }

    static public function getTagBySlug($slug)
    {
        return Centurion_Db::getSingleton('magazine/tag')->findOneBySlug($slug);
    }
}