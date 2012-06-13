<?php

require_once dirname(__FILE__) . '/../../../../../tests/TestHelper.php';

class Magazine_Test_Model_ArticleTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     *
     * @return mixed
     */
    public function testGetRelatedArticleByTag()
    {
        // Creation d'un article avec des tags
        $dataArticleArray = array();
        $dataArticleArray['title'] = 'Titre test';
        $dataArticleArray['content_type_id'] = Centurion_Db::getSingleton('core/content_type')->getContentTypeIdOf('Magazine_Model_DbTable_Article');
        $dataArticleArray['author_id'] = 1;
        $dataArticleArray['is_online'] = 1;
        $dataArticleArray['is_promoted'] = 0;
        $dataArticleArray['is_headline'] = 0;
        $dataArticleArray['has_comment'] = 0;
        $dataArticleArray['has_twitter'] = 0;
        $dataArticleArray['has_facebook'] = 0;
        $dataArticleArray['has_push_social'] = 0;
        $dataArticleArray['has_related'] = 0;
        $dataArticleArray['moderator_id'] = 1;
        $dataArticleArray['is_article'] = 1;
        $dataArticleArray['is_homepage'] = 0;

        $articleForm = new Magazine_Form_Model_Article();
        $articleForm->populate($dataArticleArray);
        $articleForm->save();

        // Ajout de l'article dans un magazine
        $magazineRow = Centurion_Db::getSingleton('magazine/magazine')->fetchRow();


        $this->markTestIncomplete();
    }

    public function testDeleteArticle()
    {
        // Création d'un magazine
        list($magazineRow, ) = Centurion_Db::getSingleton('magazine/magazine')->getOrCreate(array('title' => 'Test delete article'));

        // Création d'un auteur
        list($authorRow, ) = Centurion_Db::getSingleton('magazine/author')->getOrCreate(array(
            'lastname'      => 'test',
            'firstname'     => 'article'
        ));

        // Création d'un article
        list($articleRow, ) = Centurion_Db::getSingleton('magazine/article')->getOrCreate(array(
            'title'         => 'Test delete article',
            'picture_id'    => '88888888',
            'author_id'     => $authorRow->id,
        ));

        //Zend_Debug::dump($articleRow->getTable()->getIndex());die;

        $data = array(
            'article_id'    => $articleRow->id,
            'magazine_id'   => $magazineRow->id
        );
        // Création de la relation magazine/article
        list($magazineArticleRow) = Centurion_Db::getSingleton('magazine/magazine_article')->getOrCreate($data);

        // Suppression de l'article
        $articleRow->delete();

        $this->assertNull(Centurion_Db::getSingleton('magazine/article')->findOneByTitle('Test delete article'));
        $this->assertNotNull(Centurion_Db::getSingleton('magazine/magazine')->findOneByTitle('Test delete article'));

        // Vérification que le magazine existe toujours et que l'article n'existe plus
    }
}
