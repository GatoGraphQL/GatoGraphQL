<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\Schema\TypeConditionServiceInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\RuntimeLocation;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;

class CoreGlobalObjectTypeFieldResolver extends AbstractGlobalObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?TypeConditionServiceInterface $typeConditionService = null;

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
    final protected function getTypeConditionService(): TypeConditionServiceInterface
    {
        if ($this->typeConditionService === null) {
            /** @var TypeConditionServiceInterface */
            $typeConditionService = $this->instanceManager->getInstance(TypeConditionServiceInterface::class);
            $this->typeConditionService = $typeConditionService;
        }
        return $this->typeConditionService;
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
            '_isObjectType' => $this->__('Indicate if the object is of a given type', 'gatographql'),
            '_implements' => $this->__('Indicate if the object implements a given interface', 'gatographql'),
            '_isInUnionType' => $this->__('Indicate if the object is part of a given union type', 'gatographql'),
            '_isTypeOrImplements' => $this->__('Indicate if the object is of a given type or implements a given interface', 'gatographql'),
            '_isTypeOrImplementsAll' => $this->__('Indicate if the object is all of the given types or interfaces', 'gatographql'),
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
            ['_isObjectType' => 'type'] => $this->__('The type name to compare against', 'gatographql'),
            ['_implements' => 'interface'] => $this->__('The interface name to compare against', 'gatographql'),
            ['_isInUnionType' => 'type'] => $this->__('The union type name to compare against', 'gatographql'),
            ['_isTypeOrImplements' => 'typeOrInterface'] => $this->__('The type or interface name to compare against', 'gatographql'),
            ['_isTypeOrImplementsAll' => 'typesOrInterfaces'] => $this->__('The types and interface names to compare against', 'gatographql'),
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
                return $this->getTypeConditionService()->isObjectType(
                    $objectTypeResolver,
                    $fieldDataAccessor->getValue('type'),
                );

            case '_implements':
                return $this->getTypeConditionService()->implementsInterface(
                    $objectTypeResolver,
                    $fieldDataAccessor->getValue('interface'),
                );

            case '_isInUnionType':
                $unionTypeResolver = $this->getTypeConditionService()->getUnionTypeResolver($fieldDataAccessor->getValue('type'));
                if ($unionTypeResolver === null) {
                    return false;
                }
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
