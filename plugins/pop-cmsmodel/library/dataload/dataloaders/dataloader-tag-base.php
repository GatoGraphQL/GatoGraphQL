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

    public function executeGetData(array $ids): array
    {
        if ($ids) {
            $query = array(
                'include' => $ids
            );
            $taxonomyapi = \PoP\Taxonomy\FunctionAPI_Factory::getInstance();
            return $taxonomyapi->getTags($query);
        }
        
        return array();
    }
    
    // function executeGetData(array $ids): array {
    
    //     $taxonomyapi = \PoP\Taxonomy\FunctionAPI_Factory::getInstance();
    //     if ($tag_id = $ids[0]) {
    //         return array($taxonomyapi->getTag($tag_id));
    //     }
    //     return array();
    // }
}
