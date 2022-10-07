<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\TypeObjectTypeFieldResolver as UpstreamTypeObjectTypeFieldResolver;

class TypeObjectTypeFieldResolver extends UpstreamTypeObjectTypeFieldResolver
{
    public function getPriorityToAttachToClasses(): int
    {
        // Higher priority => Process first
        return 100;
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
        ];
    }

    /**
     * Only use this fieldResolver when parameter `namespaced` is provided. Otherwise, use the default implementation
     */
    public function resolveCanProcess(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return match ($field->getName()) {
            'name' => $field->hasArgument('namespaced'),
            default => parent::resolveCanProcess($objectTypeResolver, $field),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'name' => [
                'namespaced' => $this->getBooleanScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['name' => 'namespaced'] => $this->__('Namespace type name?', 'graphql-server'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['name' => 'namespaced'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var NamedTypeInterface */
        $type = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'name':
                if ($fieldDataAccessor->getValue('namespaced')) {
                    return $type->getNamespacedName();
                }
                return $type->getElementName();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
