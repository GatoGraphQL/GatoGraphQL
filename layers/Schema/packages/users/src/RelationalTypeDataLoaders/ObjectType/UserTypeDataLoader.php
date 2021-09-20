<?php

declare(strict_types=1);

namespace PoPSchema\Users\RelationalTypeDataLoaders\ObjectType;

use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;

class UserTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        NameResolverInterface $nameResolver,
        ModuleProcessorManagerInterface $moduleProcessorManager,
        protected UserTypeAPIInterface $userTypeAPI,
    ) {
        parent::__construct(
            $hooksAPI,
            $instanceManager,
            $nameResolver,
            $moduleProcessorManager,
        );
    }

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
        return $this->userTypeAPI->getUsers($query, $options);
    }

    public function executeQueryIDs($query): array
    {
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
