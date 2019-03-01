<?php
namespace PoP\CMSModel;

abstract class Dataloader_TagBase extends \PoP\Engine\QueryDataDataloader
{
    public function getDataquery()
    {
        return GD_DATAQUERY_TAG;
    }

    public function getDatabaseKey()
    {
        return GD_DATABASE_KEY_TAGS;
    }

    public function getFieldprocessor()
    {
        return GD_DATALOAD_FIELDPROCESSOR_TAGS;
    }

    public function executeGetData($ids)
    {
        if ($ids) {
            $query = array(
                'include' => implode(', ', $ids)
            );
            $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
            return $cmsapi->getTags($query);
        }
        
        return array();
    }
    
    // function executeGetData($ids) {
    
    //        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    //     if ($tag_id = $ids[0]) {
    //         return array($cmsapi->getTag($tag_id));
    //     }
    //     return array();
    // }
}
