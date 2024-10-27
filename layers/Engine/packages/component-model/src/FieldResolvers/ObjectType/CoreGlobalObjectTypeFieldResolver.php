<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionTokens;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\RuntimeLocation;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;

class CoreGlobalObjectTypeFieldResolver extends AbstractGlobalObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?TypeRegistryInterface $typeRegistry = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        if ($this->typeRegistry === null) {
            /** @var TypeRegistryInterface */
            $typeRegistry = $this->instanceManager->getInstance(TypeRegistryInterface::class);
            $this->typeRegistry = $typeRegistry;
        }
        return $this->typeRegistry;
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            '_isObjectType',
            '_implements',
            '_isInUnionType',
            '_isTypeOrImplements',
            '_isTypeOrImplementsAll',
        ];
    }

    /**
     * Do not expose these fields in the Schema
     */
    public function skipExposingFieldInSchema(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return !$this->exposeCoreFunctionalityGlobalFields();
    }

    public function exposeCoreFunctionalityGlobalFields(): bool
    {
        /**
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->exposeCoreFunctionalityGlobalFields();
    }

    /**
     * Only process internally
     */
    public function resolveCanProcessField(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        if ($this->exposeCoreFunctionalityGlobalFields()) {
            return true;
        }

        /**
         * Enable when executed within the GraphQL server
         */
        if ($field->getLocation() instanceof RuntimeLocation) {
            return true;
        }

        /**
         * Disable when invoked from the GraphQL API
         */
        return false;
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            '_isObjectType' => $this->getBooleanScalarTypeResolver(),
            '_implements' => $this->getBooleanScalarTypeResolver(),
            '_isInUnionType' => $this->getBooleanScalarTypeResolver(),
            '_isTypeOrImplements' => $this->getBooleanScalarTypeResolver(),
            '_isTypeOrImplementsAll' => $this->getBooleanScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            '_isObjectType',
            '_implements',
            '_isInUnionType',
            '_isTypeOrImplements',
            '_isTypeOrImplementsAll'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            '_isObjectType' => $this->__('Indicate if the object is of a given type', 'component-model'),
            '_implements' => $this->__('Indicate if the object implements a given interface', 'component-model'),
            '_isInUnionType' => $this->__('Indicate if the object is part of a given union type', 'component-model'),
            '_isTypeOrImplements' => $this->__('Indicate if the object is of a given type or implements a given interface', 'component-model'),
            '_isTypeOrImplementsAll' => $this->__('Indicate if the object is all of the given types or interfaces', 'component-model'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            '_isObjectType' => [
                'type' => $this->getStringScalarTypeResolver(),
            ],
            '_implements' => [
                'interface' => $this->getStringScalarTypeResolver(),
            ],
            '_isInUnionType' => [
                'type' => $this->getStringScalarTypeResolver(),
            ],
            '_isTypeOrImplements' => [
                'typeOrInterface' => $this->getStringScalarTypeResolver(),
            ],
            '_isTypeOrImplementsAll' => [
                'typesOrInterfaces' => $this->getStringScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['_isObjectType' => 'type'] => $this->__('The type name to compare against', 'component-model'),
            ['_implements' => 'interface'] => $this->__('The interface name to compare against', 'component-model'),
            ['_isInUnionType' => 'type'] => $this->__('The union type name to compare against', 'component-model'),
            ['_isTypeOrImplements' => 'typeOrInterface'] => $this->__('The type or interface name to compare against', 'component-model'),
            ['_isTypeOrImplementsAll' => 'typesOrInterfaces'] => $this->__('The types and interface names to compare against', 'component-model'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['_isObjectType' => 'type'],
            ['_implements' => 'interface'],
            ['_isInUnionType' => 'type'],
            ['_isTypeOrImplements' => 'typeOrInterface']
                => SchemaTypeModifiers::MANDATORY,
            ['_isTypeOrImplementsAll' => 'typesOrInterfaces']
                => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        switch ($fieldDataAccessor->getFieldName()) {
            case '_isObjectType':
                $typeName = $fieldDataAccessor->getValue('type');
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

            case '_implements':
                $interface = $fieldDataAccessor->getValue('interface');
                $implementedInterfaceTypeResolvers = $objectTypeResolver->getImplementedInterfaceTypeResolvers();
                // If the provided interface contains the namespace separator, then compare by qualifiedInterface
                $useNamespaced = str_contains($interface, SchemaDefinitionTokens::NAMESPACE_SEPARATOR);
                $implementedInterfaceNames = array_map(
                    function (InterfaceTypeResolverInterface $interfaceTypeResolver) use ($useNamespaced): string {
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
                            function (InterfaceTypeResolverInterface $interfaceTypeResolver): string {
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

            case '_isInUnionType':
                $unionTypeName = $fieldDataAccessor->getValue('type');
                $unionTypeResolvers = $this->getTypeRegistry()->getUnionTypeResolvers();
                $foundUnionTypeResolver = null;
                /**
                 * If the provided unionTypeName contains the namespace separator, then compare by qualifiedType
                 * @see https://github.com/graphql/graphql-spec/issues/163
                 */
                $isNamespacedUnionTypeName = str_contains($unionTypeName, SchemaDefinitionTokens::NAMESPACE_SEPARATOR);
                foreach ($unionTypeResolvers as $unionTypeResolver) {
                    if (
                        $unionTypeName === $unionTypeResolver->getTypeName()
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

            case '_isTypeOrImplements':
                $_isObjectType = $objectTypeResolver->resolveValue(
                    $object,
                    new LeafField(
                        '_isObjectType',
                        null,
                        [
                            new Argument(
                                'type',
                                new Literal(
                                    $fieldDataAccessor->getValue('typeOrInterface'),
                                    $fieldDataAccessor->getField()->getLocation()
                                ),
                                $fieldDataAccessor->getField()->getLocation()
                            ),
                        ],
                        [],
                        $fieldDataAccessor->getField()->getLocation()
                    ),
                    $objectTypeFieldResolutionFeedbackStore,
                );
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return null;
                }
                if ($_isObjectType) {
                    return true;
                }
                $implements = $objectTypeResolver->resolveValue(
                    $object,
                    new LeafField(
                        '_implements',
                        null,
                        [
                            new Argument(
                                'interface',
                                new Literal(
                                    $fieldDataAccessor->getValue('typeOrInterface'),
                                    $fieldDataAccessor->getField()->getLocation()
                                ),
                                $fieldDataAccessor->getField()->getLocation()
                            ),
                        ],
                        [],
                        $fieldDataAccessor->getField()->getLocation()
                    ),
                    $objectTypeFieldResolutionFeedbackStore,
                );
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return null;
                }
                if ($implements) {
                    return true;
                }
                $isInUnionType = $objectTypeResolver->resolveValue(
                    $object,
                    new LeafField(
                        '_isInUnionType',
                        null,
                        [
                            new Argument(
                                'type',
                                new Literal(
                                    $fieldDataAccessor->getValue('typeOrInterface'),
                                    $fieldDataAccessor->getField()->getLocation()
                                ),
                                $fieldDataAccessor->getField()->getLocation()
                            ),
                        ],
                        [],
                        $fieldDataAccessor->getField()->getLocation()
                    ),
                    $objectTypeFieldResolutionFeedbackStore,
                );
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return null;
                }
                if ($isInUnionType) {
                    return true;
                }
                return false;

            case '_isTypeOrImplementsAll':
                foreach ($fieldDataAccessor->getValue('typesOrInterfaces') as $typeOrInterface) {
                    $isTypeOrInterface = $objectTypeResolver->resolveValue(
                        $object,
                        new LeafField(
                            '_isTypeOrImplements',
                            null,
                            [
                                new Argument(
                                    'typeOrInterface',
                                    new Literal(
                                        $typeOrInterface,
                                        $fieldDataAccessor->getField()->getLocation()
                                    ),
                                    $fieldDataAccessor->getField()->getLocation()
                                ),
                            ],
                            [],
                            $fieldDataAccessor->getField()->getLocation()
                        ),
                        $objectTypeFieldResolutionFeedbackStore,
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

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
