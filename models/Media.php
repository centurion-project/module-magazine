<?php

class Magazine_Model_Media
{
    public static function getMedias($tags = null, $order = null, $isValidOnly = false)
    {
        $select = Centurion_Db::getSingleton('magazine/media')->select(true);

        if (!is_null($tags)) {
            $ids = array();
            foreach ($tags as $tag) {
                $ids[] = $tag->id;
            }

            if (count($ids) > 0) {
                $select->setIntegrityCheck(false);
                $select->joinInner('magazine_tag_proxy', 'magazine_tag_proxy.proxy_model = magazine_media.content_type_id and magazine_tag_proxy.proxy_pk = magazine_media.id and magazine_tag_proxy.tag_id in (' . implode(', ', $ids) . ')');
                $select->group('magazine_media.id');
            }
        }

        if (true === $isValidOnly) {
            $select->filter(array('is_valid' => 1));
        }

        if (null !== $order) {
            $select->order($order);
        }

        return $select->fetchAll();
    }

    public static function getTagsList()
    {
        $select = Centurion_Db::getSingleton('magazine/tag')
            ->select(true)
            ->setIntegrityCheck(false)
            ->joinInner('magazine_tag_proxy', 'magazine_tag_proxy.tag_id = magazine_tag.id and magazine_tag_proxy.proxy_model = 13')
            ->group('magazine_tag.id');

        return $select->fetchAll();
    }

    static public function getMedia($id)
    {
        return Centurion_Db::getSingleton('magazine/media')->findOneById($id);
    }

    static public function getRandomMedia()
    {
        $mediaSelect = Centurion_Db::getSingleton('magazine/media')
            ->select(true)
            ->order('RAND()');

        return $mediaSelect->fetchRow();

    }

    /**
     * Retourne tous les medias de la médiathèque contribués par un user/author
     *
     * @static
     * @param User_Model_DbTable_Row_Profile $userRow
     * @return Centurion_Db_Table_Select
     * @throws Centurion_Model_Exception
     */
    public static function getContributedMediaByUser($userRow)
    {
        if (null != $userRow && $userRow instanceof Magazine_Model_DbTable_Row_Author) {
            $select = Centurion_Db::getSingleton('magazine/media')->select(true);
            return $select;
        } else {
            throw new Centurion_Model_Exception('Bad argument type, expected instance of User_Model_DbTable_Profile, get '.gettype($userRow));
        }
    }
}