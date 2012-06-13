<?php

class Magazine_View_Helper_RowsetSlice extends Zend_View_Helper_Abstract
{
    /**
     * @param Centurion_Db_Table_Rowset_Abstract $rowset
     * @param int $offset
     * @param null $length
     * @param bool $keepKeys
     * @return array
     *
     * @todo : description
     * @todo : test unitaire
     */
    public function rowsetSlice(Centurion_Db_Table_Rowset_Abstract $rowset, $offset = 0, $length = null, $keepKeys = false)
    {
        $data = iterator_to_array($rowset);

        return array_slice($data, $offset, $length, $keepKeys);
    }
}
