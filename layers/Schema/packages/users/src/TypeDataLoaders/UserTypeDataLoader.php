<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

class UserTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    public function getObjects(array $ids): array
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $ret = array();
        foreach ($ids as $user_id) {
            $ret[] = $userTypeAPI->getUserById($user_id);
        }
        return $ret;
    }

    public function getDataFromIdsQuery(array $ids): array
    {
        return [
            'include' => $ids,
        ];
    }

    protected function getOrderbyDefault()
    {
        return $this->nameResolver->getName('popcms:dbcolumn:orderby:users:name');
    }

    protected function getOrderDefault()
    {
        return 'ASC';
    }

    protected function getQueryHookName()
    {
        // Get the role either from a provided attr, and allow PoP User Platform to set the default role
        return 'UserTypeDataLoader:query';
    }

    public function executeQuery($query, array $options = [])
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        return $userTypeAPI->getUsers($query, $options);
    }

    public function executeQueryIds($query): array
    {
        // $query['fields'] = 'ID';
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return (array)$this->executeQuery($query, $options);
    }
}
