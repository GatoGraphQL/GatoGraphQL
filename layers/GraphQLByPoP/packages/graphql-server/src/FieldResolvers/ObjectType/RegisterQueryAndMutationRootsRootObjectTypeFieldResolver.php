<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;

/**
 * Add connections to the QueryRoot and MutationRoot types,
 * so they can be accessed to generate the schema
 */
class RegisterQueryAndMutationRootsRootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
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
        if ($vars['nested-mutations-enabled'] && !ComponentConfiguration::addConnectionFromRootToQueryRootAndMutationRoot()) {
            return [];
        }
        return array_merge(
            [
                'queryRoot',
            ],
            APIComponentConfiguration::enableMutations() ?
            [
                'mutationRoot',
            ] : []
        );
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'queryRoot' => $this->translationAPI->__('Get the Query Root type', 'graphql-server'),
            'mutationRoot' => $this->translationAPI->__('Get the Mutation Root type', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'queryRoot':
                return $this->instanceManager->getInstance(QueryRootObjectTypeResolver::class);
            case 'mutationRoot':
                return $this->instanceManager->getInstance(MutationRootObjectTypeResolver::class);
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}
