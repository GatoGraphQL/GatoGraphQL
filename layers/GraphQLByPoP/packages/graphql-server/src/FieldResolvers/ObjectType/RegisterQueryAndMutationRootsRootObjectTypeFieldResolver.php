<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
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

    final protected function getQueryRootObjectTypeResolver(): QueryRootObjectTypeResolver
    {
        if ($this->queryRootObjectTypeResolver === null) {
            /** @var QueryRootObjectTypeResolver */
            $queryRootObjectTypeResolver = $this->instanceManager->getInstance(QueryRootObjectTypeResolver::class);
            $this->queryRootObjectTypeResolver = $queryRootObjectTypeResolver;
        }
        return $this->queryRootObjectTypeResolver;
    }
    final protected function getMutationRootObjectTypeResolver(): MutationRootObjectTypeResolver
    {
        if ($this->mutationRootObjectTypeResolver === null) {
            /** @var MutationRootObjectTypeResolver */
            $mutationRootObjectTypeResolver = $this->instanceManager->getInstance(MutationRootObjectTypeResolver::class);
            $this->mutationRootObjectTypeResolver = $mutationRootObjectTypeResolver;
        }
        return $this->mutationRootObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
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
     *
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            $moduleConfiguration->enableNestedMutations()
            && !$moduleConfiguration->addConnectionFromRootToQueryRootAndMutationRoot()
        ) {
            return [];
        }

        /** @var ComponentModelModuleConfiguration */
        $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        return array_merge(
            [
                'queryRoot',
            ],
            $moduleConfiguration->enableMutations() ?
            [
                'mutationRoot',
            ] : []
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'queryRoot' => $this->__('Get the Query Root type', 'graphql-server'),
            'mutationRoot' => $this->__('Get the Mutation Root type', 'graphql-server'),
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
