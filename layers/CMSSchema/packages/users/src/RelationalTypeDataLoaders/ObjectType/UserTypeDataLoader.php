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

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return [
            'include' => $ids,
        ];
    }

    protected function getOrderbyDefault(): string
    {
        return $this->getNameResolver()->getName('popcms:dbcolumn:orderby:users:name');
    }

    protected function getOrderDefault(): string
    {
        return 'ASC';
    }

    protected function getQueryHookName(): string
    {
        // Get the role either from a provided attr, and allow PoP User Platform to set the default role
        return 'UserTypeDataLoader:query';
    }

    /**
     * @return mixed[]
     */
    public function executeQuery(array $query, array $options = []): array
    {
        return $this->getUserTypeAPI()->getUsers($query, $options);
    }

    /**
     * @param array<string,mixed> $query
     * @return array<string|int>
     */
    public function executeQueryIDs(array $query): array
    {
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
