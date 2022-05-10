<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use GraphQLByPoP\GraphQLServer\Component;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinition\RootObjectTypeSchemaDefinitionProvider;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;
use PoPAPI\API\ObjectModels\SchemaDefinition\RootObjectTypeSchemaDefinitionProvider as UpstreamRootObjectTypeSchemaDefinitionProvider;
use PoPAPI\API\Schema\SchemaDefinitionService;

class GraphQLSchemaDefinitionService extends SchemaDefinitionService implements GraphQLSchemaDefinitionServiceInterface
{
    private ?QueryRootObjectTypeResolver $queryRootObjectTypeResolver = null;
    private ?MutationRootObjectTypeResolver $mutationRootObjectTypeResolver = null;

    final public function setQueryRootObjectTypeResolver(QueryRootObjectTypeResolver $queryRootObjectTypeResolver): void
    {
        $this->queryRootObjectTypeResolver = $queryRootObjectTypeResolver;
    }
    final protected function getQueryRootObjectTypeResolver(): QueryRootObjectTypeResolver
    {
        return $this->queryRootObjectTypeResolver ??= $this->instanceManager->getInstance(QueryRootObjectTypeResolver::class);
    }
    final public function setMutationRootObjectTypeResolver(MutationRootObjectTypeResolver $mutationRootObjectTypeResolver): void
    {
        $this->mutationRootObjectTypeResolver = $mutationRootObjectTypeResolver;
    }
    final protected function getMutationRootObjectTypeResolver(): MutationRootObjectTypeResolver
    {
        return $this->mutationRootObjectTypeResolver ??= $this->instanceManager->getInstance(MutationRootObjectTypeResolver::class);
    }

    /**
     * If nested mutations are enabled, use "Root".
     * Otherwise, use "Query"
     */
    public function getSchemaQueryRootObjectTypeResolver(): ObjectTypeResolverInterface
    {
        if (App::getState('nested-mutations-enabled')) {
            return $this->getSchemaRootObjectTypeResolver();
        }

        return $this->getQueryRootObjectTypeResolver();
    }

    /**
     * If nested mutations are enabled, use "Root".
     * Otherwise, use "Mutation"
     */
    public function getSchemaMutationRootObjectTypeResolver(): ?ObjectTypeResolverInterface
    {
        /** @var ComponentModelComponentConfiguration */
        $componentConfiguration = App::getComponent(ComponentModelComponent::class)->getConfiguration();
        if (!$componentConfiguration->enableMutations()) {
            return null;
        }
        if (App::getState('nested-mutations-enabled')) {
            return $this->getSchemaRootObjectTypeResolver();
        }

        return $this->getMutationRootObjectTypeResolver();
    }

    /**
     * @todo Implement
     */
    public function getSchemaSubscriptionRootTypeResolver(): ?ObjectTypeResolverInterface
    {
        return null;
    }

    protected function createRootObjectTypeSchemaDefinitionProvider(
        RootObjectTypeResolver $rootObjectTypeResolver,
    ): UpstreamRootObjectTypeSchemaDefinitionProvider {
        return new RootObjectTypeSchemaDefinitionProvider($rootObjectTypeResolver);
    }

    /**
     * Global fields are only added if enabled
     */
    protected function skipExposingGlobalFieldsInSchema(): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return !$componentConfiguration->exposeGlobalFieldsInGraphQLSchema();
    }
}
