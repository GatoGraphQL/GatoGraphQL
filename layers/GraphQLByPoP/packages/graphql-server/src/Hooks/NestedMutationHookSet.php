<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\Interface\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\Object\RootTypeResolver;
use PoP\Hooks\AbstractHookSet;

class NestedMutationHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            HookHelpers::getHookNameToFilterField(),
            array($this, 'maybeFilterFieldName'),
            10,
            5
        );

        $this->hooksAPI->addFilter(
            RootTypeResolver::HOOK_DESCRIPTION,
            array($this, 'getRootTypeDescription')
        );
    }

    public function getRootTypeDescription(string $description): string
    {
        return sprintf(
            $this->translationAPI->__('%s. Available when \'nested mutations\' is enabled', 'graphql-server'),
            $description
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
        array $interfaceTypeResolverClasses,
        string $fieldName
    ): bool {
        $vars = ApplicationState::getVars();
        if ($vars['nested-mutations-enabled']) {
            return $include;
        }
        if ($objectTypeOrInterfaceTypeResolver instanceof InterfaceTypeResolverInterface) {
            return $include;
        }
        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $objectTypeOrInterfaceTypeResolver;
        /** @var ObjectTypeFieldResolverInterface */
        $objectTypeFieldResolver = $objectTypeOrInterfaceTypeFieldResolver;
        $graphQLSchemaDefinitionService = GraphQLSchemaDefinitionServiceFacade::getInstance();
        if (
            $include
            && !in_array(get_class($objectTypeResolver), [
                $graphQLSchemaDefinitionService->getRootTypeResolverClass(),
                $graphQLSchemaDefinitionService->getMutationRootTypeResolverClass(),
            ])
            && $objectTypeFieldResolver->resolveFieldMutationResolverClass($objectTypeResolver, $fieldName) !== null
        ) {
            return false;
        }

        return $include;
    }
}
