<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\Component;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\API\Component as APIComponent;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;

/**
 * Add connections to the QueryRoot and MutationRoot types,
 * so they can be accessed to generate the schema
 */
class RegisterQueryAndMutationRootsRootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
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

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * Register the fields for the Standard GraphQL server only,
     * and when nested mutations are disabled, and when not additionally
     * appending the QueryRoot and Mutation Root to the schema
     */
    public function getFieldNamesToResolve(): array
    {
        $vars = ApplicationState::getVars();
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if ($vars['nested-mutations-enabled'] && !$componentConfiguration->addConnectionFromRootToQueryRootAndMutationRoot()) {
            return [];
        }
        /** @var APIComponentConfiguration */
        $componentConfiguration = App::getComponent(APIComponent::class)->getConfiguration();
        return array_merge(
            [
                'queryRoot',
            ],
            $componentConfiguration->enableMutations() ?
            [
                'mutationRoot',
            ] : []
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'queryRoot' => $this->getTranslationAPI()->__('Get the Query Root type', 'graphql-server'),
            'mutationRoot' => $this->getTranslationAPI()->__('Get the Mutation Root type', 'graphql-server'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'queryRoot' => $this->getQueryRootObjectTypeResolver(),
            'mutationRoot' => $this->getMutationRootObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
