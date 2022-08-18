<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class NestedMutationHookSet extends AbstractHookSet
{
    private ?GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService = null;

    final public function setGraphQLSchemaDefinitionService(GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService): void
    {
        $this->graphQLSchemaDefinitionService = $graphQLSchemaDefinitionService;
    }
    final protected function getGraphQLSchemaDefinitionService(): GraphQLSchemaDefinitionServiceInterface
    {
        return $this->graphQLSchemaDefinitionService ??= $this->instanceManager->getInstance(GraphQLSchemaDefinitionServiceInterface::class);
    }

    protected function init(): void
    {
        App::addFilter(
            HookHelpers::getHookNameToFilterField(),
            $this->maybeFilterFieldName(...),
            10,
            4
        );
    }

    /**
     * For the standard GraphQL server:
     * If nested mutations are disabled, then remove registering fieldNames
     * when they have a MutationResolver for types other than the Root and MutationRoot
     */
    public function maybeFilterFieldName(
        bool $include,
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName
    ): bool {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableNestedMutations()) {
            return $include;
        }
        if ($objectTypeOrInterfaceTypeResolver instanceof InterfaceTypeResolverInterface) {
            return $include;
        }
        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $objectTypeOrInterfaceTypeResolver;
        /** @var ObjectTypeFieldResolverInterface */
        $objectTypeFieldResolver = $objectTypeOrInterfaceTypeFieldResolver;
        if (
            $include
            && (
                $objectTypeResolver !== $this->getGraphQLSchemaDefinitionService()->getSchemaRootObjectTypeResolver()
                && $objectTypeResolver !== $this->getGraphQLSchemaDefinitionService()->getSchemaMutationRootObjectTypeResolver()
            )
            && $objectTypeFieldResolver->getFieldMutationResolver($objectTypeResolver, $fieldName) !== null
        ) {
            return false;
        }

        return $include;
    }
}
