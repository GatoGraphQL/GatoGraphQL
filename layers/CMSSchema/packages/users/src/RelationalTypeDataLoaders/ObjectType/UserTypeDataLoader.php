<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;

class UserTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }

    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return [
            'include' => $ids,
        ];
    }

    protected function getOrderbyDefault()
    {
        return $this->getNameResolver()->getName('popcms:dbcolumn:orderby:users:name');
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
        return $this->getUserTypeAPI()->getUsers($query, $options);
    }

    public function executeQueryIDs($query): array
    {
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
