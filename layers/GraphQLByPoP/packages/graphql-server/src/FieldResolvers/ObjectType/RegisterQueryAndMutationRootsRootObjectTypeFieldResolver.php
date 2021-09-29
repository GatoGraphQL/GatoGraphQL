<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Add connections to the QueryRoot and MutationRoot types,
 * so they can be accessed to generate the schema
 */
class RegisterQueryAndMutationRootsRootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected QueryRootObjectTypeResolver $queryRootObjectTypeResolver;
    protected MutationRootObjectTypeResolver $mutationRootObjectTypeResolver;

    #[Required]
    public function autowireRegisterQueryAndMutationRootsRootObjectTypeFieldResolver(
        QueryRootObjectTypeResolver $queryRootObjectTypeResolver,
        MutationRootObjectTypeResolver $mutationRootObjectTypeResolver,
    ): void {
        $this->queryRootObjectTypeResolver = $queryRootObjectTypeResolver;
        $this->mutationRootObjectTypeResolver = $mutationRootObjectTypeResolver;
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
        return match ($fieldName) {
            'queryRoot' => $this->translationAPI->__('Get the Query Root type', 'graphql-server'),
            'mutationRoot' => $this->translationAPI->__('Get the Mutation Root type', 'graphql-server'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'queryRoot':
                return $this->queryRootObjectTypeResolver;
            case 'mutationRoot':
                return $this->mutationRootObjectTypeResolver;
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}
