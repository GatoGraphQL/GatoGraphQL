<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\API\Schema\SchemaDefinitionService;
use Symfony\Contracts\Service\Attribute\Required;

class GraphQLSchemaDefinitionService extends SchemaDefinitionService implements GraphQLSchemaDefinitionServiceInterface
{
    protected QueryRootObjectTypeResolver $queryRootObjectTypeResolver;
    protected MutationRootObjectTypeResolver $mutationRootObjectTypeResolver;

    #[Required]
    final public function autowireGraphQLSchemaDefinitionService(QueryRootObjectTypeResolver $queryRootObjectTypeResolver, MutationRootObjectTypeResolver $mutationRootObjectTypeResolver): void
    {
        $this->queryRootObjectTypeResolver = $queryRootObjectTypeResolver;
        $this->mutationRootObjectTypeResolver = $mutationRootObjectTypeResolver;
    }

    /**
     * If nested mutations are enabled, use "Root".
     * Otherwise, use "Query"
     */
    public function getQueryRootObjectTypeResolver(): ObjectTypeResolverInterface
    {
        $vars = ApplicationState::getVars();
        if ($vars['nested-mutations-enabled']) {
            return $this->getRootObjectTypeResolver();
        }

        return $this->queryRootObjectTypeResolver;
    }

    /**
     * If nested mutations are enabled, use "Root".
     * Otherwise, use "Mutation"
     */
    public function getMutationRootObjectTypeResolver(): ?ObjectTypeResolverInterface
    {
        if (!APIComponentConfiguration::enableMutations()) {
            return null;
        }
        $vars = ApplicationState::getVars();
        if ($vars['nested-mutations-enabled']) {
            return $this->getRootObjectTypeResolver();
        }

        return $this->mutationRootObjectTypeResolver;
    }

    /**
     * @todo Implement
     */
    public function getSubscriptionRootTypeResolver(): ?ObjectTypeResolverInterface
    {
        return null;
    }
}
