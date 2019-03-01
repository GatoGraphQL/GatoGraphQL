<?php
namespace PoP\CMSModel;

abstract class Dataloader_TagListBase extends Dataloader_TagBase
{
    use Dataloader_ListTrait;

    public function getDataFromIdsQuery($ids)
    {
        $query = array(
            'include' => implode(', ', $ids)
        );
        return $query;
    }
    
    protected function getOrderbyDefault()
    {
        return 'count';
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }
    
    public function executeQuery($query)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        return $cmsapi->getTags($query);
    }
    
    public function executeQueryIds($query)
    {
    
        // Retrieve only ids
        $query['fields'] = 'ids';
        return $this->executeQuery($query);
    }
}
