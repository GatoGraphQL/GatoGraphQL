<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class RootRelationalFieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_QUERYROOT = 'dataload-relationalfields-queryroot';
    public final const COMPONENT_DATALOAD_RELATIONALFIELDS_MUTATIONROOT = 'dataload-relationalfields-mutationroot';

    private ?GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService = null;

    final public function setGraphQLSchemaDefinitionService(GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService): void
    {
        $this->graphQLSchemaDefinitionService = $graphQLSchemaDefinitionService;
    }
    final protected function getGraphQLSchemaDefinitionService(): GraphQLSchemaDefinitionServiceInterface
    {
        return $this->graphQLSchemaDefinitionService ??= $this->instanceManager->getInstance(GraphQLSchemaDefinitionServiceInterface::class);
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_QUERYROOT,
            self::COMPONENT_DATALOAD_RELATIONALFIELDS_MUTATIONROOT,
        );
    }

    /**
     * @return string|int|array<string|int>|null
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     */
    public function getObjectIDOrIDs(Component $component, array &$props, array &$data_properties): string|int|array|null
    {
        if (App::getState('does-api-query-have-errors')) {
            return null;
        }
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_QUERYROOT:
                return QueryRoot::ID;
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MUTATIONROOT:
                return MutationRoot::ID;
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(Component $component): ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_QUERYROOT:
                return $this->getGraphQLSchemaDefinitionService()->getSchemaQueryRootObjectTypeResolver();
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_MUTATIONROOT:
                return $this->getGraphQLSchemaDefinitionService()->getSchemaMutationRootObjectTypeResolver();
        }

        return parent::getRelationalTypeResolver($component);
    }
}
