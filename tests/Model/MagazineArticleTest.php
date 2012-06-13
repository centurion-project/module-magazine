<?php

require_once dirname(__FILE__) . '/../../../../../tests/TestHelper.php';

class Magazine_Test_Model_MagazineArticleTest extends PHPUnit_Framework_TestCase
{
    //TODO: make it protected
    public function getMagazineForTest()
    {
        list($magazineRow, ) = Centurion_Db::getSingleton('magazine/magazine')->getOrCreate(array(
            'title' => 'Test magazine getMagazineForTest'
        ));

        return $magazineRow;
    }

    public function getMagazineArticleForTest($magazineId)
    {
        $articleTable = Centurion_Db::getSingleton('magazine/article');
        list($articleRow, ) = $articleTable->getOrCreate(array(
                                                              'title' => 'Test article',
                                                              'author_id' => $this->getAuthorForTest()->id
                                                         )
        );

        $articleRow->is_online = 1;
        $articleRow->published_at = Zend_Date::now()->subDay(1)->toString(Centurion_Date::MYSQL_DATETIME);

        $articleRow->save();

        list($magazineArticle, ) = Centurion_Db::getSingleton('magazine/magazine_article')->getOrCreate(array(
                            'article_id' => $articleRow->id,
                            'magazine_id' => $magazineId,
                       ));

        return $magazineArticle;
    }

    public function getAuthorForTest()
    {
        list($authorRow, ) = Centurion_Db::getSingleton('magazine/author')->getOrCreate(array(
            'lastname' => 'Lastname',
            'firstname' => 'Firstname',
        ));

        return $authorRow;
    }

    public function getUserForTest()
    {
        return Centurion_Db::getSingleton('auth/user')->fetchRow();
    }

    public function getRandomOnlineArticleForTest($magazineId)
    {
        return Magazine_Model_MagazineArticle::getSelectOnlineArticle($magazineId)->fetchRow();
    }

    /**
     * @todo: vérifier la création d'un magazine
     * @todo: vérifier la création d'un article
     * @todo: ajouter test $fetchHeadline
     * @todo: ajouter test $fetchHeadlineChilds
     */
    public function testComportementOfFunctionGetSelectOnlineArticle()
    {
        $magazineRow = $this->getMagazineForTest();

        // Vérifie que l'objet retourné par la fonction a bien la bonne classe : Zend_Db_Table_Select
        $selectWithId = Magazine_Model_MagazineArticle::getSelectOnlineArticle($magazineRow->id);
        $this->assertInstanceOf('Zend_Db_Table_Select', $selectWithId);

        // Vérifier en passant une row à la fonction au lieu d'un id que l'objet retourné est identique dans les deux cas
        $selectWithRow = Magazine_Model_MagazineArticle::getSelectOnlineArticle($magazineRow);
        $this->assertEquals($selectWithId, $selectWithRow);

        //Vérifier le comportement de la function en lui passant n'importe quoi
        try {
            Magazine_Model_MagazineArticle::getSelectOnlineArticle('cecinestpasunid');
            $this->fail('La fonction aurait du lever une exception car on ne lui a pas passé le bon type d\'arguement');
        } catch (Centurion_Model_Exception $e) {
            //All is good
        }

        //Vérifier le comportement de la function en lui passant un nombre (string)
        try {
            Magazine_Model_MagazineArticle::getSelectOnlineArticle('1');
        } catch (Centurion_Model_Exception $e) {
            $this->fail('La fonction ne fonctionne pas avec un nombre (string) comme argument');
        }

        //Vérifier le comportement de la function en lui passant un nombre (int)
        try {
            Magazine_Model_MagazineArticle::getSelectOnlineArticle(1);
        } catch (Centurion_Model_Exception $e) {
            $this->fail('La fonction ne fonctionne pas avec un nombre (int) comme argument');
        }
    }


    /**
     * @static
     * @return array
     */
    public static function getDataForArticleConstraint()
    {
        return array(
            array(1, '1980-10-10 00:00:00', 1),
            array(0, '1980-10-10 00:00:00', 0),
            array(1, '2050-10-10 00:00:00', 0),
            array(0, '2050-10-10 00:00:00', 0),
            array(1, null, 0),
            array(0, null, 0),
            array(1, '0000-00-00 00:00:00', 0),
            array(0, '0000-00-00 00:00:00', 0),
        );
    }

    /**
     * @depends testComportementOfFunctionGetSelectOnlineArticle
     * @dataProvider getDataForArticleConstraint
     */
    public function testOnlineConstraintForFunctionGetSelectOnlineArticle($is_online, $published_at, $mustBeFound)
    {
        $magazineRow = $this->getMagazineForTest();
        $authorRow = $this->getAuthorForTest();
        $userRow = $this->getUserForTest();

        $articleTable = Centurion_Db::getSingleton('magazine/article');
        list($articleRow, ) = $articleTable->getOrCreate(array(
            'title' => 'Test article testOnlineConstraintForFunctionGetSelectOnlineArticle',
            'author_id' => $authorRow->id
            )
        );

        $articleRow->is_online = $is_online;
        $articleRow->published_at = $published_at;
        $articleRow->save();

        list($magazineArticleRow, ) = Centurion_Db::getSingleton('magazine/magazine_article')->getOrCreate(array(
            'article_id' => $articleRow->id,
            'magazine_id' => $magazineRow->id,
        ));

        $selectForOnlineArticle = Magazine_Model_MagazineArticle::getSelectOnlineArticle($magazineRow->id);
        $onlineArticleRowset = $selectForOnlineArticle->fetchAll();

        $isFound = false;
        foreach ($onlineArticleRowset as $onlineArticleRow) {
            if ($onlineArticleRow->article_id == $articleRow->id) {
                $isFound = true;
            }
        }
        if (false === $isFound && $mustBeFound == true) {
            $this->fail('L\'article créé est en ligne mais n\'a pas été retourné par la function Magazine_Model_MagazineArticle::getSelectOnlineArticle()');
        }
        if (true === $isFound && $mustBeFound == false) {
            $this->fail('L\'article créé est hors ligne mais a été retourné par la function Magazine_Model_MagazineArticle::getSelectOnlineArticle()');
        }
    }

    /**
     * @depends testOnlineConstraintForFunctionGetSelectOnlineArticle
     */
    public function testComportementOfFunctionGetMagazineArticleBySlug()
    {
        $magazineRow = $this->getMagazineForTest();
        $magazineArticleRow = $this->getMagazineArticleForTest($magazineRow->id);

        $this->assertNotNull($magazineRow);
        $this->assertNotNull($magazineArticleRow);

        // Vérifie que l'objet retourné par la fonction a bien la bonne classe : Magazine_Model_DbTable_Row_MagazineArticle
        $magazineArticleGetBySlugRow = Magazine_Model_MagazineArticle::getMagazineArticleBySlug($magazineRow->id, $magazineArticleRow->article__slug);
        $this->assertInstanceOf('Magazine_Model_DbTable_Row_MagazineArticle', $magazineArticleGetBySlugRow);

        //Vérifier que la fonction retourne nul dans le cas d'un slug qui n'existe pas
        $magazineArticleGetBySlugRow = Magazine_Model_MagazineArticle::getMagazineArticleBySlug($magazineRow->id, uniqid());
        $this->assertEquals(null, $magazineArticleGetBySlugRow);
    }

    /**
     * @depends testComportementOfFunctionGetMagazineArticleBySlug
     */
    public function testReferenceMapForFunctionGetMagazineArticleBySlug()
    {
        $magazineRow = $this->getMagazineForTest();
        $magazineArticleRow = $this->getRandomOnlineArticleForTest($magazineRow->id);

        $magazineArticleGetBySlugRow = Magazine_Model_MagazineArticle::getMagazineArticleBySlug($magazineRow->id, $magazineArticleRow->article__slug);
        $this->assertInstanceOf('Magazine_Model_DbTable_Row_Article', $magazineArticleGetBySlugRow->article);
        $this->assertInstanceOf('Magazine_Model_DbTable_Row_Magazine', $magazineArticleGetBySlugRow->magazine);
    }
}
