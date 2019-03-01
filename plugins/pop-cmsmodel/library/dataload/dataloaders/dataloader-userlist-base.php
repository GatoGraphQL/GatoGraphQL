<?php
namespace PoP\CMSModel;

abstract class Dataloader_UserListBase extends Dataloader_UserBase
{
    use Dataloader_ListTrait;

    public function getDataFromIdsQuery($ids)
    {
        $query = array(
            'include' => $ids
        );
        return $query;
    }
    
    protected function getOrderbyDefault()
    {
        return 'name';
    }

    protected function getOrderDefault()
    {
        return 'ASC';
    }

    public function getQuery($query_args)
    {
        $query = $this->getMetaQuery($query_args);

        // Get the role either from a provided attr, and allow PoP User Platform to set the default role
        if ($role = apply_filters(
            'Dataloader_UserListBase:query:role',
            $query_args['role']
        )
        ) {
            $query['role'] = $role;
        }

        return $query;
    }
    
    public function executeQuery($query)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        return $cmsapi->getUsers($query);
    }
    
    public function executeQueryIds($query)
    {
    
        // Retrieve only ids
        $query['fields'] = 'ID';
        return $this->executeQuery($query);
    }
}
