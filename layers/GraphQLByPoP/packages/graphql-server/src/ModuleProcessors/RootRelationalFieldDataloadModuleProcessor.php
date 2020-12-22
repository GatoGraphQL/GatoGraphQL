<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ModuleProcessors;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade;

class RootRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    public const MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT = 'dataload-relationalfields-queryroot';
    public const MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT = 'dataload-relationalfields-mutationroot';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT],
        );
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT:
                return QueryRoot::ID;
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT:
                return MutationRoot::ID;
        }
        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getTypeResolverClass(array $module): ?string
    {
        $graphQLSchemaDefinitionService = GraphQLSchemaDefinitionServiceFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT:
                return $graphQLSchemaDefinitionService->getQueryRootTypeResolverClass();
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT:
                return $graphQLSchemaDefinitionService->getMutationRootTypeResolverClass();
        }

        return parent::getTypeResolverClass($module);
    }
}
