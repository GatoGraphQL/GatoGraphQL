<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Schema\SchemaDefinitionService;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use GraphQLByPoP\GraphQLServer\TypeResolvers\QueryRootTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\MutationRootTypeResolver;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;

class GraphQLSchemaDefinitionService extends SchemaDefinitionService implements GraphQLSchemaDefinitionServiceInterface
{
    public function getQueryRootTypeSchemaKey(): string
    {
        $queryTypeResolverClass = $this->getQueryRootTypeResolverClass();
        return $this->getTypeResolverTypeSchemaKey($queryTypeResolverClass);
    }

    /**
     * If nested mutations are enabled, use "Root".
     * Otherwise, use "Query"
     */
    public function getQueryRootTypeResolverClass(): string
    {
        $vars = ApplicationState::getVars();
        return $vars['nested-mutations-enabled'] ?
            $this->getRootTypeResolverClass()
            : QueryRootTypeResolver::class;
    }

    public function getMutationRootTypeSchemaKey(): ?string
    {
        if ($mutationTypeResolverClass = $this->getMutationRootTypeResolverClass()) {
            return $this->getTypeResolverTypeSchemaKey($mutationTypeResolverClass);
        }
        return null;
    }

    /**
     * If nested mutations are enabled, use "Root".
     * Otherwise, use "Mutation"
     */
    public function getMutationRootTypeResolverClass(): ?string
    {
        if (!APIComponentConfiguration::enableMutations()) {
            return null;
        }
        $vars = ApplicationState::getVars();
        return $vars['nested-mutations-enabled'] ?
            $this->getRootTypeResolverClass()
            : MutationRootTypeResolver::class;
    }

    public function getSubscriptionRootTypeSchemaKey(): ?string
    {
        if ($subscriptionTypeResolverClass = $this->getSubscriptionRootTypeResolverClass()) {
            return $this->getTypeResolverTypeSchemaKey($subscriptionTypeResolverClass);
        }
        return null;
    }

    /**
     * @todo Implement
     */
    public function getSubscriptionRootTypeResolverClass(): ?string
    {
        return null;
    }
}
