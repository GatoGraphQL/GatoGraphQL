<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\App;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;

class UserObjectTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    public const HOOK_ALL_OBJECTS_BY_IDS_QUERY = __CLASS__ . ':all-objects-by-ids-query';

    private ?UserTypeAPIInterface $userTypeAPI = null;

    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        if ($this->userTypeAPI === null) {
            /** @var UserTypeAPIInterface */
            $userTypeAPI = $this->instanceManager->getInstance(UserTypeAPIInterface::class);
            $this->userTypeAPI = $userTypeAPI;
        }
        return $this->userTypeAPI;
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return App::applyFilters(
            self::HOOK_ALL_OBJECTS_BY_IDS_QUERY,
            [
                'include' => $ids,
            ],
            $ids
        );
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
        return 'UserObjectTypeDataLoader:query';
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
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
