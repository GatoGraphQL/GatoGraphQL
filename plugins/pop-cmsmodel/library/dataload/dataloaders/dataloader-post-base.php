<?php
namespace PoP\CMSModel;

abstract class Dataloader_PostBase extends \PoP\Engine\QueryDataDataloader
{
    public function getDataquery()
    {
        return GD_DATAQUERY_POST;
    }
    
    public function getDatabaseKey()
    {
        return GD_DATABASE_KEY_POSTS;
    }

    public function getFieldprocessor()
    {
        return GD_DATALOAD_FIELDPROCESSOR_POSTS;
    }
    
    public function executeGetData($ids)
    {
        if ($ids) {
            $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
            $query = array(
                'include' => $ids,
                'post_type' => array_keys($cmsapi->getPostTypes()) // From all post types
            );
            return $cmsapi->getPosts($query);
        }
        
        return array();
    }
    
    // function executeGetData($ids) {
    
    //     $ret = array();
    //     foreach ($ids as $post_id) {

    //         $ret[] = $this->getPost($post_id);
    //     }
    //     return $ret;
    // }

    // function getPost($post_id) {
    
    //        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    //     return $cmsapi->getPost($post_id);
    // }
}
