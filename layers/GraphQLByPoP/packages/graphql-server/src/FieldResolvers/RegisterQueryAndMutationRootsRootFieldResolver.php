<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\TypeResolvers\Object\RootTypeResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\Object\QueryRootTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\Object\MutationRootTypeResolver;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;

/**
 * Add connections to the QueryRoot and MutationRoot types,
 * so they can be accessed to generate the schema
 */
class RegisterQueryAndMutationRootsRootFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
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

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'queryRoot' => $this->translationAPI->__('Get the Query Root type', 'graphql-server'),
            'mutationRoot' => $this->translationAPI->__('Get the Mutation Root type', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'queryRoot':
                return QueryRootTypeResolver::class;
            case 'mutationRoot':
                return MutationRootTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}
