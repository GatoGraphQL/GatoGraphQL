<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ModuleProcessors;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

class RootRelationalFieldDataloadModuleProcessor extends AbstractRelationalFieldDataloadModuleProcessor
{
    public const MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT = 'dataload-relationalfields-queryroot';
    public const MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT = 'dataload-relationalfields-mutationroot';
    protected GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService;

    #[Required]
    final public function autowireRootRelationalFieldDataloadModuleProcessor(
        GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService,
    ): void {
        $this->graphQLSchemaDefinitionService = $graphQLSchemaDefinitionService;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_QUERYROOT],
            [self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT],
        );
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
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
                return $this->graphQLSchemaDefinitionService->getQueryRootTypeResolver();
            case self::MODULE_DATALOAD_RELATIONALFIELDS_MUTATIONROOT:
                return $this->graphQLSchemaDefinitionService->getMutationRootTypeResolver();
        }

        return parent::getRelationalTypeResolver($module);
    }
}
