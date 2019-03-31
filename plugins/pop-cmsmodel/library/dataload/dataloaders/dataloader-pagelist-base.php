<?php
namespace PoP\CMSModel;

abstract class Dataloader_PageListBase extends Dataloader_PostListBase
{
    public function getDataFromIdsQuery(array $ids): array
    {
        $query = parent::getDataFromIdsQuery($ids);
        
        $query['post-types'] = ['page'];
        
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
