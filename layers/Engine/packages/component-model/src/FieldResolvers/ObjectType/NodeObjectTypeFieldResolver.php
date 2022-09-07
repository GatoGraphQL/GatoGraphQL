<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\NodeInterfaceTypeFieldResolver;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class NodeObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?NodeInterfaceTypeFieldResolver $nodeInterfaceTypeFieldResolver = null;

    final public function setNodeInterfaceTypeFieldResolver(NodeInterfaceTypeFieldResolver $nodeInterfaceTypeFieldResolver): void
    {
        $this->nodeInterfaceTypeFieldResolver = $nodeInterfaceTypeFieldResolver;
    }
    final protected function getNodeInterfaceTypeFieldResolver(): NodeInterfaceTypeFieldResolver
    {
        /** @var NodeInterfaceTypeFieldResolver */
        return $this->nodeInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(NodeInterfaceTypeFieldResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractObjectTypeResolver::class,
        ];
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getNodeInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'id',
            'self',
        ];
    }

    /**
     * The "self" field is enabled only for non-introspection types,
     * and only when either it is enabled by configuration,
     * or when invoked internally within the GraphQL server,
     * as to allow for Multiple Query Execution.
     *
     * @see QueryASTTransformationService.php
     */
    public function resolveCanProcess(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        if ($field->getName() === 'self') {
            /**
             * Disable for introspection types
             */
            if ($objectTypeResolver->isIntrospectionObjectTypeResolver()) {
                return false;
            }
            /**
             * Enable for internally-executed functionality
             * (eg: Multiple Query Execution)
             */
            if ($field->getLocation() === ASTNodesFactory::getNonSpecificLocation()) {
                return true;
            }

            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            return $moduleConfiguration->enableSelfField();
        }
        return parent::resolveCanProcess(
            $objectTypeResolver,
            $field,
        );
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'self' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'self' => $this->__('The same object', 'pop-component-model'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        return match ($fieldDataAccessor->getFieldName()) {
            'id',
            'self'
                => $objectTypeResolver->getID($object),
            default
                => parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'self' => $objectTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
