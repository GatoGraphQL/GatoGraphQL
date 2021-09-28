<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

class NestedMutationHookSet extends AbstractHookSet
{
    protected GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService;
    public function __construct(
        GraphQLSchemaDefinitionServiceInterface $graphQLSchemaDefinitionService,
    ) {
        $this->graphQLSchemaDefinitionService = $graphQLSchemaDefinitionService;
    }

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            HookHelpers::getHookNameToFilterField(),
            array($this, 'maybeFilterFieldName'),
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
        if (
            $include
            && (
                $objectTypeResolver !== $this->graphQLSchemaDefinitionService->getRootTypeResolver()
                && $objectTypeResolver !== $this->graphQLSchemaDefinitionService->getMutationRootTypeResolver()
            )
            && $objectTypeFieldResolver->getFieldMutationResolver($objectTypeResolver, $fieldName) !== null
        ) {
            return false;
        }

        return $include;
    }
}
