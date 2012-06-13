<?php
class Magazine_View_Helper_GetAbstract extends Zend_View_Helper_Abstract
{
    
    public function getAbstract($row = null, $limit = 255)
    {
        if (0 == func_num_args()) {
            return $this;
        }

        if (!empty($row->abstract)) {
            return $row->abstract;
        } else {
            return $this->view->smartTextCrop($row->body, $limit);
        }
    }

    /**
     * Return article summary
     * If it's an article, returns subtitle
     * If it's an interview, return abstract, if no abstract, returns subtitle
     *
     * @param Magazine_Model_DbTable_Article $row
     * @param integer $limit
     * @return mixed
     */
    public function getArticleSummary($row = null, $limit = 255)
    {
        if (1 == $row->is_article) {
            return $this->view->smartTextCrop($row->subtitle, $limit);
        } else {
            return $this->getAbstract($row, $limit);
        }
    }
    
}