<?php
namespace PoP\CMSModel;

abstract class Dataloader_PageListBase extends Dataloader_PostListBase
{
    public function getDataFromIdsQuery($ids)
    {
        $query = parent::getDataFromIdsQuery($ids);
        
        $query['post_type'] = 'page';
        
        return $query;
    }
    
    // function executeQueryIds($query) {
    
    //        $query['fields'] = 'ids';
    //        return $this->executeQuery($query);
    // }
    
    public function getDatabaseKey()
    {
        return GD_DATABASE_KEY_PAGES;
    }
}
