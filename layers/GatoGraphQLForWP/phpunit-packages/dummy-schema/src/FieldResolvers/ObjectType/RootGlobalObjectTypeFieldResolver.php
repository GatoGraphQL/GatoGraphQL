<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummySchema\FieldResolvers\ObjectType;

use PHPUnitForGatoGraphQL\DummySchema\MutationResolvers\DummyCreateStringMutationResolver;
use PHPUnitForGatoGraphQL\DummySchema\TypeResolvers\InputObjectType\FirstLayerInputObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractGlobalObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

class RootGlobalObjectTypeFieldResolver extends AbstractGlobalObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?FirstLayerInputObjectTypeResolver $firstLayerInputObjectTypeResolver = null;
    private ?DummyCreateStringMutationResolver $dummyCreateStringMutationResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setFirstLayerInputObjectTypeResolver(FirstLayerInputObjectTypeResolver $firstLayerInputObjectTypeResolver): void
    {
        $this->firstLayerInputObjectTypeResolver = $firstLayerInputObjectTypeResolver;
    }
    final protected function getFirstLayerInputObjectTypeResolver(): FirstLayerInputObjectTypeResolver
    {
        /** @var FirstLayerInputObjectTypeResolver */
        return $this->firstLayerInputObjectTypeResolver ??= $this->instanceManager->getInstance(FirstLayerInputObjectTypeResolver::class);
    }
    final public function setDummyCreateStringMutationResolver(DummyCreateStringMutationResolver $dummyCreateStringMutationResolver): void
    {
        $this->dummyCreateStringMutationResolver = $dummyCreateStringMutationResolver;
    }
    final protected function getDummyCreateStringMutationResolver(): DummyCreateStringMutationResolver
    {
        /** @var DummyCreateStringMutationResolver */
        return $this->dummyCreateStringMutationResolver ??= $this->instanceManager->getInstance(DummyCreateStringMutationResolver::class);
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            '_dummyGlobalMutation',
            '_dummyGlobalField',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            '_dummyGlobalMutation' => $this->__('Dummy global mutation (nothing really happens, but it returs a String)', 'dummy-schema'),
            '_dummyGlobalField' => $this->__('Dummy global field', 'dummy-schema'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            '_dummyGlobalMutation' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        return match ($fieldName) {
            '_dummyGlobalMutation' => $this->getDummyCreateStringMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            '_dummyGlobalMutation' => $this->getStringScalarTypeResolver(),
            '_dummyGlobalField' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            '_dummyGlobalField' => [
                'input' => $this->getFirstLayerInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['_dummyGlobalField' => 'input'] => $this->__('First level of Input Object containing other Input Objects, to test their validation is performed', 'dummy-schema'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['_dummyGlobalField' => 'input'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case '_dummyGlobalField':
                return sprintf(
                    $this->__('Received: %s', 'dummy-schema'),
                    json_encode(
                        $fieldDataAccessor->getValue('input')
                    )
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
