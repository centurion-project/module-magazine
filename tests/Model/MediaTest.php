<?php

require_once dirname(__FILE__) . '/../../../../../tests/TestHelper.php';

class Magazine_Test_Model_MediaTest extends PHPUnit_Framework_TestCase
{
    /**
     * Create a fake auth_user for test
     * @return mixed
     */
    protected function getUserForTest()
    {
        list($userRow, ) = Centurion_Db::getSingleton('auth/user')->getOrCreate(array(
            'username' => 'auth user for test',
        ));

        return $userRow;
    }

    /**
     * Create a fake author linked with auth_user for test
     * @return mixed
     */
    protected function getAuthorWithUserForTest()
    {
        $userRow = $this->getUserForTest();

        list($authorRow, ) = Centurion_Db::getSingleton('magazine/author')->getOrCreate(array(
            'lastname' => 'lastname with auth_user for test',
            'firstname' => 'firstname with auth_user for test',
            'user_id' => $userRow->id,
        ));

        return $authorRow;
    }

    /**
     * Create a fake author without auth_user linked for test
     * @return mixed
     */
    protected function getAuthorForTest()
    {
        list($authorRow, ) = Centurion_Db::getSingleton('magazine/author')->getOrCreate(array(
            'lastname' => 'lastname with no auth_user for test',
            'firstname' => 'firstname with no auth_user for test',
        ));

        return $authorRow;
    }

    /**
     * Get fake media for test
     * @return mixed
     */
    protected function getMediaForTest()
    {
        $authorRow = $this->getAuthorForTest();

        list($mediaRow, ) = Centurion_Db::getSingleton('magazine/media')->getOrCreate(array(
            'title' => 'megazine media for test',
            'author_id' => $authorRow->id,
        ));

        return $mediaRow;
    }


    public function testComportementOfFunctionGetContributedMediaBytUser()
    {
        //$userRow as string
        try {
            $select = Magazine_Model_Media::getContributedMediaByUser('user');
            $this->fail('Erreur : La fonction aurait du lever une exception');
        } catch(Centurion_Model_Exception $e) {
            //All is good
        }

        //$userRow as integer
        try {
            $select = Magazine_Model_Media::getContributedMediaByUser(1);
            $this->fail('Erreur : La fonction aurait du lever une exception');
        } catch(Centurion_Model_Exception $e) {
            //All is good
        }

        //$userRow as integer
        try {
            $select = Magazine_Model_Media::getContributedMediaByUser(array('user'));
            $this->fail('Erreur : La fonction aurait du lever une exception');
        } catch(Centurion_Model_Exception $e) {
            //All is good
        }

        $mediaRow = $this->getMediaForTest();

        /**
         * Test with an author linked with an auth_user
         */
        $authorRowLinkedWithUser = $this->getAuthorWithUserForTest();
        try {
            $select = Magazine_Model_Media::getContributedMediaByUser($authorRowLinkedWithUser);
            $this->assertInstanceOf('Centurion_Db_Table_Select', $select);
        } catch(Centurion_Model_Exception $e) {
            //All is good
        }

        /**
         * Test with an author not linked with an auth_user
         */
        $authorRowWithoutUser = $this->getAuthorForTest();
        try {
            $select = Magazine_Model_Media::getContributedMediaByUser($authorRowWithoutUser);
            $this->assertInstanceOf('Centurion_Db_Table_Select', $select);
        } catch(Centurion_Model_Exception $e) {
            //All is good
        }
    }
}
