<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\API\Component as APIComponent;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\API\Schema\SchemaDefinitionService;

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
        /** @var APIComponentConfiguration */
        $componentConfiguration = App::getComponent(APIComponent::class)->getConfiguration();
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
}
