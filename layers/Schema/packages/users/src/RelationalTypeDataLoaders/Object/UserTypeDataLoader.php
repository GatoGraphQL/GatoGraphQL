<?php

declare(strict_types=1);

namespace PoPSchema\Users\RelationalTypeDataLoaders\Object;

use PoP\ComponentModel\RelationalTypeDataLoaders\Object\AbstractObjectTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

class UserTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
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

    public function executeQuery($query, array $options = []): array
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        return $userTypeAPI->getUsers($query, $options);
    }

    public function executeQueryIDs($query): array
    {
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
