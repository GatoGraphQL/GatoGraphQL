<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Schema\SchemaDefinitionService;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GraphQLSchemaDefinitionService extends SchemaDefinitionService implements GraphQLSchemaDefinitionServiceInterface
{
    /**
     * Can't use autowiring or it produces a circular reference exception
     */
    protected ?QueryRootObjectTypeResolver $queryRootObjectTypeResolver = null;
    protected ?MutationRootObjectTypeResolver $mutationRootObjectTypeResolver = null;

    public function getQueryRootTypeSchemaKey(): string
    {
        $queryTypeResolver = $this->getQueryRootTypeResolver();
        return $this->getTypeResolverTypeSchemaKey($queryTypeResolver);
    }

    /**
     * If nested mutations are enabled, use "Root".
     * Otherwise, use "Query"
     */
    public function getQueryRootTypeResolver(): ObjectTypeResolverInterface
    {
        $vars = ApplicationState::getVars();
        if ($vars['nested-mutations-enabled']) {
            return $this->getRootTypeResolver();
        }

        if ($this->queryRootObjectTypeResolver === null) {
            $this->queryRootObjectTypeResolver = $this->instanceManager->getInstance(QueryRootObjectTypeResolver::class);
        }
        return $this->queryRootObjectTypeResolver;
    }

    public function getMutationRootTypeSchemaKey(): ?string
    {
        if ($mutationTypeResolver = $this->getMutationRootTypeResolver()) {
            return $this->getTypeResolverTypeSchemaKey($mutationTypeResolver);
        }
        return null;
    }

    /**
     * If nested mutations are enabled, use "Root".
     * Otherwise, use "Mutation"
     */
    public function getMutationRootTypeResolver(): ?ObjectTypeResolverInterface
    {
        if (!APIComponentConfiguration::enableMutations()) {
            return null;
        }
        $vars = ApplicationState::getVars();
        if ($vars['nested-mutations-enabled']) {
            return $this->getRootTypeResolver();
        }

        if ($this->mutationRootObjectTypeResolver === null) {
            $this->mutationRootObjectTypeResolver = $this->instanceManager->getInstance(MutationRootObjectTypeResolver::class);
        }
        return $this->mutationRootObjectTypeResolver;
    }

    public function getSubscriptionRootTypeSchemaKey(): ?string
    {
        if ($subscriptionTypeResolver = $this->getSubscriptionRootTypeResolver()) {
            return $this->getTypeResolverTypeSchemaKey($subscriptionTypeResolver);
        }
        return null;
    }

    /**
     * @todo Implement
     */
    public function getSubscriptionRootTypeResolver(): ?ObjectTypeResolverInterface
    {
        return null;
    }
}
