<?php
class Magazine_MediaController extends Centurion_Controller_Action
{
    /*
    * Gestion du multi magazine
    * Récupération du magazine courrant (dans le cas d'IR, on triche, il y a qu'un seul magazine)
    * Si il n'existe pas > 404
    */
    public function init()
    {
        $this->view->magazineRow = Magazine_Model_Magazine::getDefaultMagazine();

        $this->forward404If(is_null($this->view->magazineRow));
    }

    public function listAction()
    {
        $this->view->tagRowSet = Magazine_Model_Media::getTagsList();
        $this->view->mediaRowSet = Magazine_Model_Media::getMedias($this->view->tagRowSet, null, true);

        // Navigation
        //$this->view->currentRoute = 'ir_mediatheque_list';
    }

    public function getAction()
    {
        $id = $this->_getParam('id');

        $mediaRow = Magazine_Model_Media::getMedia($id);

        /*
        var data = {
        title: "Châteauneuf-du-pape",
                description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam",
                pics:[
                    // Big picture, thumb, title, date
                    ['http://lorempixel.com/1024/768/sports/1/', 'http://lorempixel.com/55/31/sports/1/', 'Dégustation', '24 Juillet 2011'],
                    ['http://lorempixel.com/1024/768/sports/2/', 'http://lorempixel.com/55/31/sports/2/', 'Titre autre', '13 Février 2012'],
                    ['http://lorempixel.com/1024/768/sports/3/', 'http://lorempixel.com/55/31/sports/3/', 'Titre autre', '13 Février 2012'],
                    ['http://lorempixel.com/1024/768/sports/1/', 'http://lorempixel.com/55/31/sports/1/', 'Titre autre', '13 Février 2012'],
                    ['http://lorempixel.com/1024/768/sports/2/', 'http://lorempixel.com/55/31/sports/2/', 'Titre autre', '13 Février 2012'],
                    ['http://lorempixel.com/1024/768/sports/3/', 'http://lorempixel.com/55/31/sports/3/', 'Titre autre', '13 Février 2012'],
                    ['http://lorempixel.com/1024/768/sports/1/', 'http://lorempixel.com/55/31/sports/1/', 'Titre autre', '13 Février 2012'],
                    ['http://lorempixel.com/1024/768/sports/2/', 'http://lorempixel.com/55/31/sports/2/', 'Titre autre', '13 Février 2012'],
                    ['http://lorempixel.com/1024/768/sports/3/', 'http://lorempixel.com/55/31/sports/3/', 'Titre autre', '13 Février 2012'],
                    ['http://lorempixel.com/1024/768/sports/1/', 'http://lorempixel.com/55/31/sports/1/', 'Titre autre', '13 Février 2012'],
                    ['http://lorempixel.com/1024/768/sports/2/', 'http://lorempixel.com/55/31/sports/2/', 'Titre autre', '13 Février 2012'],
                    ['http://lorempixel.com/1024/768/sports/3/', 'http://lorempixel.com/55/31/sports/3/', 'Titre autre', '13 Février 2012'],
                    ['http://lorempixel.com/1024/768/sports/4/', 'http://lorempixel.com/55/31/sports/4/', 'Titre autre', '13 Février 2012']
                ]
            };
        */

        $data = array();

        $tags = $mediaRow->tags->toArray();
        $tagNames = array();
        foreach ($tags as $tag)
        {
            $tagNames[] = $tag['name'];
        }

        asort($tagNames);

        if (isset($tagNames[0])) {
            $data['title'] = array_shift($tagNames);
        } else {
            $data['title'] = '';
        }

        $data['description'] = implode(' ', $tagNames);
        $data['pics'] = array();


        $mediaRowSet = Magazine_Model_Media::getMedias($mediaRow->tags, new Zend_Db_Expr('id = ' . $mediaRow->id . ' desc'));

        foreach ($mediaRowSet as $row) {

            $type = 'image'; //@TODO : Il faut rajouter dans le model pour préciser le type de contribution, image ou video

            $big = $row->media->getStaticUrl(array('resize' => array('maxHeight' => 1024)));
            $thumb = $row->media->getStaticUrl(array('cropcenterresize' => array('width' => 55, 'height' => 31)));

            $title = $row->title;
            $description = $this->view->smartTextCrop()->simpleTextCrop($row->description, 50);
            if (null != $row->description) {
                $title = $title.', '.$description;
            }

            $date = $row->getDateObjectBy('created_at')->get(Zend_Date::DATE_MEDIUM);

            $author = '';
            if (null != $row->author && $row->author->fullname) {
                $author = $row->author->fullname;
            }
            if ('' != $author) {
                $author = $this->view->translate('Par').' '.$author.', ';
            }

            $data['pics'][] = array(
                $type,
                $big,
                $thumb,
                $title,
                $author.$this->view->translate('le ').$date,
            );
        }

        $this->_helper->json($data);
        die();
    }
}
