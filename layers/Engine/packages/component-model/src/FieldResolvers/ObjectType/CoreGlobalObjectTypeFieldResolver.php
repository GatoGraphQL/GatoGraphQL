<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionTokens;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CoreGlobalObjectTypeFieldResolver extends AbstractGlobalObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?TypeRegistryInterface $typeRegistry = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setTypeRegistry(TypeRegistryInterface $typeRegistry): void
    {
        $this->typeRegistry = $typeRegistry;
    }
    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        return $this->typeRegistry ??= $this->instanceManager->getInstance(TypeRegistryInterface::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'typeName',
            'namespace',
            'qualifiedTypeName',
            'isObjectType',
            'implements',
            'isInUnionType',
            'isTypeOrImplements',
            'isTypeOrImplementsAll',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'typeName' => $this->getStringScalarTypeResolver(),
            'namespace' => $this->getStringScalarTypeResolver(),
            'qualifiedTypeName' => $this->getStringScalarTypeResolver(),
            'isObjectType' => $this->getBooleanScalarTypeResolver(),
            'implements' => $this->getBooleanScalarTypeResolver(),
            'isInUnionType' => $this->getBooleanScalarTypeResolver(),
            'isTypeOrImplements' => $this->getBooleanScalarTypeResolver(),
            'isTypeOrImplementsAll' => $this->getBooleanScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'typeName',
            'namespace',
            'qualifiedTypeName',
            'isObjectType',
            'implements',
            'isInUnionType',
            'isTypeOrImplements',
            'isTypeOrImplementsAll'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'typeName' => $this->__('The object\'s type', 'component-model'),
            'namespace' => $this->__('The object\'s namespace', 'component-model'),
            'qualifiedTypeName' => $this->__('The object\'s namespace + type', 'component-model'),
            'isObjectType' => $this->__('Indicate if the object is of a given type', 'component-model'),
            'implements' => $this->__('Indicate if the object implements a given interface', 'component-model'),
            'isInUnionType' => $this->__('Indicate if the object is part of a given union type', 'component-model'),
            'isTypeOrImplements' => $this->__('Indicate if the object is of a given type or implements a given interface', 'component-model'),
            'isTypeOrImplementsAll' => $this->__('Indicate if the object is all of the given types or interfaces', 'component-model'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'isObjectType' => [
                'type' => $this->getStringScalarTypeResolver(),
            ],
            'implements' => [
                'interface' => $this->getStringScalarTypeResolver(),
            ],
            'isInUnionType' => [
                'type' => $this->getStringScalarTypeResolver(),
            ],
            'isTypeOrImplements' => [
                'typeOrInterface' => $this->getStringScalarTypeResolver(),
            ],
            'isTypeOrImplementsAll' => [
                'typeOrInterfaces' => $this->getStringScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['isObjectType' => 'type'] => $this->__('The type name to compare against', 'component-model'),
            ['implements' => 'interface'] => $this->__('The interface name to compare against', 'component-model'),
            ['isInUnionType' => 'type'] => $this->__('The union type name to compare against', 'component-model'),
            ['isTypeOrImplements' => 'typeOrInterface'] => $this->__('The type or interface name to compare against', 'component-model'),
            ['isTypeOrImplementsAll' => 'typeOrInterfaces'] => $this->__('The types and interface names to compare against', 'component-model'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['isObjectType' => 'type'],
            ['implements' => 'interface'],
            ['isInUnionType' => 'type'],
            ['isTypeOrImplements' => 'typeOrInterface']
                => SchemaTypeModifiers::MANDATORY,
            ['isTypeOrImplementsAll' => 'typeOrInterfaces']
                => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'typeName':
                return $objectTypeResolver->getTypeName();

            case 'namespace':
                return $objectTypeResolver->getNamespace();

            case 'qualifiedTypeName':
                return $objectTypeResolver->getNamespacedTypeName();

            case 'isObjectType':
                $typeName = $fieldArgs['type'];
                // If the provided typeName contains the namespace separator, then compare by qualifiedType
                if (str_contains($typeName, SchemaDefinitionTokens::NAMESPACE_SEPARATOR)) {
                    /**
                     * @todo Replace the code below with:
                     *
                     *     return $typeName === $objectTypeResolver->getNamespacedTypeName();
                     *
                     * Currently, because the GraphQL spec doesn't support namespaces,
                     * we are using "_" as the namespace separator, instead of "/".
                     * But this character can also be part of the Type name!
                     * So only temporarily, compare both the namespaced and the
                     * normal type name, until can use "/".
                     *
                     * @see https://github.com/graphql/graphql-spec/issues/163
                     */
                    return
                        $typeName === $objectTypeResolver->getNamespacedTypeName()
                        || $typeName === $objectTypeResolver->getTypeName();
                }
                return $typeName === $objectTypeResolver->getTypeName();

            case 'implements':
                $interface = $fieldArgs['interface'];
                $implementedInterfaceTypeResolvers = $objectTypeResolver->getImplementedInterfaceTypeResolvers();
                // If the provided interface contains the namespace separator, then compare by qualifiedInterface
                $useNamespaced = str_contains($interface, SchemaDefinitionTokens::NAMESPACE_SEPARATOR);
                $implementedInterfaceNames = array_map(
                    function (InterfaceTypeResolverInterface $interfaceTypeResolver) use ($useNamespaced) {
                        if ($useNamespaced) {
                            return $interfaceTypeResolver->getNamespacedTypeName();
                        }
                        return $interfaceTypeResolver->getTypeName();
                    },
                    $implementedInterfaceTypeResolvers
                );
                /**
                 * @todo Remove the block of code below.
                 *
                 * Currently, because the GraphQL spec doesn't support namespaces,
                 * we are using "_" as the namespace separator, instead of "/".
                 * But this character can also be part of the Interface name!
                 * So only temporarily, also add the interface names to the
                 * array to compare, until can use "/".
                 *
                 * @see https://github.com/graphql/graphql-spec/issues/163
                 *
                 * -- Begin code --
                 */
                if ($useNamespaced) {
                    $implementedInterfaceNames = array_merge(
                        $implementedInterfaceNames,
                        array_map(
                            function (InterfaceTypeResolverInterface $interfaceTypeResolver) {
                                return $interfaceTypeResolver->getTypeName();
                            },
                            $implementedInterfaceTypeResolvers
                        )
                    );
                }
                /**
                 * -- End code --
                 */
                return in_array($interface, $implementedInterfaceNames);

            case 'isInUnionType':
                $unionTypeName = $fieldArgs['type'];
                $unionTypeResolvers = $this->getTypeRegistry()->getUnionTypeResolvers();
                $foundUnionTypeResolver = null;
                /**
                 * If the provided unionTypeName contains the namespace separator, then compare by qualifiedType
                 * @see https://github.com/graphql/graphql-spec/issues/163
                 */
                $isNamespacedUnionTypeName = str_contains($unionTypeName, SchemaDefinitionTokens::NAMESPACE_SEPARATOR);
                foreach ($unionTypeResolvers as $unionTypeResolver) {
                    if ($unionTypeName === $unionTypeResolver->getTypeName()
                        || ($isNamespacedUnionTypeName && $unionTypeName === $unionTypeResolver->getNamespacedTypeName())
                    ) {
                        $foundUnionTypeResolver = $unionTypeResolver;
                        break;
                    }
                }
                if ($foundUnionTypeResolver === null) {
                    return false;
                }
                /** @var UnionTypeResolverInterface */
                $unionTypeResolver = $foundUnionTypeResolver;
                return $unionTypeResolver->getTargetObjectTypeResolver($object) === $objectTypeResolver;

            case 'isTypeOrImplements':
                $isObjectType = $objectTypeResolver->resolveValue(
                    $object,
                    $this->getFieldQueryInterpreter()->getField(
                        'isObjectType',
                        [
                            'type' => $fieldArgs['typeOrInterface'],
                        ]
                    ),
                    $variables,
                    $expressions,
                    $objectTypeFieldResolutionFeedbackStore,
                    $options
                );
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return null;
                }
                if ($isObjectType) {
                    return true;
                }
                $implements = $objectTypeResolver->resolveValue(
                    $object,
                    $this->getFieldQueryInterpreter()->getField(
                        'implements',
                        [
                            'interface' => $fieldArgs['typeOrInterface'],
                        ]
                    ),
                    $variables,
                    $expressions,
                    $objectTypeFieldResolutionFeedbackStore,
                    $options
                );
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return null;
                }
                if ($implements) {
                    return true;
                }
                $isInUnionType = $objectTypeResolver->resolveValue(
                    $object,
                    $this->getFieldQueryInterpreter()->getField(
                        'isInUnionType',
                        [
                            'type' => $fieldArgs['typeOrInterface'],
                        ]
                    ),
                    $variables,
                    $expressions,
                    $objectTypeFieldResolutionFeedbackStore,
                    $options
                );
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return null;
                }
                if ($isInUnionType) {
                    return true;
                }
                return false;

            case 'isTypeOrImplementsAll':
                foreach ($fieldArgs['typeOrInterfaces'] as $typeOrInterface) {
                    $isTypeOrInterface = $objectTypeResolver->resolveValue(
                        $object,
                        $this->getFieldQueryInterpreter()->getField(
                            'isTypeOrImplements',
                            [
                                'typeOrInterface' => $typeOrInterface,
                            ]
                        ),
                        $variables,
                        $expressions,
                        $objectTypeFieldResolutionFeedbackStore,
                        $options
                    );
                    if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                        return null;
                    }
                    if (!$isTypeOrInterface) {
                        return false;
                    }
                }
                return true;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}
