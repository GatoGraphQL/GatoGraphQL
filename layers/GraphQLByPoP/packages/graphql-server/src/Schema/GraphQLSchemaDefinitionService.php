<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Schema\SchemaDefinitionService;
use Symfony\Contracts\Service\Attribute\Required;

class GraphQLSchemaDefinitionService extends SchemaDefinitionService implements GraphQLSchemaDefinitionServiceInterface
{
    protected QueryRootObjectTypeResolver $queryRootObjectTypeResolver;
    protected MutationRootObjectTypeResolver $mutationRootObjectTypeResolver;

    #[Required]
    public function autowireGraphQLSchemaDefinitionService(QueryRootObjectTypeResolver $queryRootObjectTypeResolver, MutationRootObjectTypeResolver $mutationRootObjectTypeResolver): void
    {
        $this->queryRootObjectTypeResolver = $queryRootObjectTypeResolver;
        $this->mutationRootObjectTypeResolver = $mutationRootObjectTypeResolver;
    }

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
