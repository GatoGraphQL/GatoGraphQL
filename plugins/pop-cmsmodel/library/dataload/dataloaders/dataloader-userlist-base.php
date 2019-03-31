<?php
namespace PoP\CMSModel;

abstract class Dataloader_UserListBase extends Dataloader_UserBase
{
    use Dataloader_ListTrait;

    public function getDataFromIdsQuery(array $ids): array
    {
        $query = array(
            'include' => $ids
        );
        return $query;
    }
    
    protected function getOrderbyDefault()
    {
        return \PoP\CMS\NameResolver_Factory::getInstance()->getName('popcms:dbcolumn:orderby:users:name');
    }

    protected function getOrderDefault()
    {
        return 'ASC';
    }

    // public function getQuery($query_args)
    // {
    //     $query = $this->getMetaQuery($query_args);

    //     // Get the role either from a provided attr, and allow PoP User Platform to set the default role
    //     return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
    //         'Dataloader_UserListBase:query',
    //         $query,
    //         $query_args
    //     );
    // }

    protected function getQueryHookName() {

        // Get the role either from a provided attr, and allow PoP User Platform to set the default role
        return 'Dataloader_UserListBase:query';
    }
    
    public function executeQuery($query, array $options = [])
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        return $cmsapi->getUsers($query, $options);
    }
    
    public function executeQueryIds($query)
    {
        // $query['fields'] = 'ID';
        $options = [
            'return-type' => POP_RETURNTYPE_IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
