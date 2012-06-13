<?php

/**
 * TODO : Changer les relations entre un objet et un article
 * Actuellement les relations avec un article sont lié à la table magazine_article mais un article n'existe pas
 * sans sa relation avec un magazine.
 * Il faudra changer les relations actuelles vers la table magazine_magazine_article.
 * En attendant, utiliser la methode getMagazineArticleRow()
 *
 */
class Magazine_Model_DbTable_Article extends Centurion_Db_Table_Abstract
{
    protected $_name = 'magazine_article';
    
    protected $_primary = 'id';
    
    protected $_rowClass = 'Magazine_Model_DbTable_Row_Article';
    
    protected $_meta = array('verboseName'   => 'article',
                             'verbosePlural' => 'articles');


    protected $_referenceMap = array(
        'author' => array(
            'columns'       => 'author_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Magazine_Model_DbTable_Author',
            'onDelete'      => self::SET_NULL,
            'onUpdate'      => self::CASCADE,
        ),
        'cover' =>  array(
            'columns'       => 'cover_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Media_Model_DbTable_File',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::RESTRICT
        ),
        'picture' =>  array(
            'columns'       => 'picture_id',
            'refColumns'    => 'id',
            'refTableClass' => 'Media_Model_DbTable_File',
            'onDelete'      => self::CASCADE,
            'onUpdate'      => self::RESTRICT
        ),
    );
    
    protected $_manyDependentTables = array(
        'tags' => array(
           'refTableClass'      =>  'Magazine_Model_DbTable_Tag',
           'intersectionTable'  =>  'Magazine_Model_DbTable_TagProxy',
           'reflocal'           =>  'article',
           'refforeign'         =>  'tag',
        ),
        'categories' => array(
           'refTableClass'      =>  'Magazine_Model_DbTable_Category',
           'intersectionTable'  =>  'Magazine_Model_DbTable_ArticleCategory',
           'reflocal'           =>  'article',
           'refforeign'         =>  'category'
        ),
        'magazines'   =>  array(
           'refTableClass'      =>  'Magazine_Model_DbTable_Magazine',
           'intersectionTable'  =>  'Magazine_Model_DbTable_MagazineArticle',
           'columns'            =>  array('local'     =>  'article_id',
                                          'foreign'   =>  'magazine_id')
        ),
        'childs'   =>  array(
           'refTableClass'      =>  'Magazine_Model_DbTable_Article',
           'intersectionTable'  =>  'Magazine_Model_DbTable_ArticleArticle',
           'reflocal'           =>  'parent2',
           'refforeign'         =>  'child2'
        ),
    );
}
