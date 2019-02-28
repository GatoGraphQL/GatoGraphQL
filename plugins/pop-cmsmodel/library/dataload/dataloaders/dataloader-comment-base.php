<?php
namespace PoP\CMSModel;

abstract class Dataloader_CommentBase extends \PoP\Engine\QueryDataDataloader
{
    public function getDataquery()
    {
        return GD_DATAQUERY_COMMENT;
    }

    public function getDatabaseKey()
    {
        return GD_DATABASE_KEY_COMMENTS;
    }

    public function getFieldprocessor()
    {
        return GD_DATALOAD_FIELDPROCESSOR_COMMENTS;
    }
    
    public function executeGetData($ids)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $ret = array();
        foreach ($ids as $id) {
            $ret[] = $cmsapi->getComment($id);
        }

        return $ret;
    }
}
