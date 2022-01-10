<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ModuleProcessors;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class RootRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    public const MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT = 'dataload-relationalfields-queryroot';
    public const MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT = 'dataload-relationalfields-mutationroot';

    private ?GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService = null;

    final public function setGraphQLSchemaDefinitionService(GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService): void
    {
        $this->graphQLSchemaDefinitionService = $graphQLSchemaDefinitionService;
    }
    final protected function getGraphQLSchemaDefinitionService(): GraphQLSchemaDefinitionServiceInterface
    {
        return $this->graphQLSchemaDefinitionService ??= $this->instanceManager->getInstance(GraphQLSchemaDefinitionServiceInterface::class);
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT],
        );
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array | null
    {
        if (App::getState('does-api-query-have-errors')) {
            return null;
        }
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT:
                return QueryRoot::ID;
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT:
                return MutationRoot::ID;
        }
        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT:
                return $this->getGraphQLSchemaDefinitionService()->getSchemaQueryRootObjectTypeResolver();
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT:
                return $this->getGraphQLSchemaDefinitionService()->getSchemaMutationRootObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }
}
