<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummySchema\FieldResolvers\ObjectType;

use PHPUnitForGatoGraphQL\DummySchema\MutationResolvers\DummyCreateStringMutationResolver;
use PHPUnitForGatoGraphQL\DummySchema\TypeResolvers\InputObjectType\FirstLayerInputObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?FirstLayerInputObjectTypeResolver $firstLayerInputObjectTypeResolver = null;
    private ?DummyCreateStringMutationResolver $dummyCreateStringMutationResolver = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getFirstLayerInputObjectTypeResolver(): FirstLayerInputObjectTypeResolver
    {
        if ($this->firstLayerInputObjectTypeResolver === null) {
            /** @var FirstLayerInputObjectTypeResolver */
            $firstLayerInputObjectTypeResolver = $this->instanceManager->getInstance(FirstLayerInputObjectTypeResolver::class);
            $this->firstLayerInputObjectTypeResolver = $firstLayerInputObjectTypeResolver;
        }
        return $this->firstLayerInputObjectTypeResolver;
    }
    final protected function getDummyCreateStringMutationResolver(): DummyCreateStringMutationResolver
    {
        if ($this->dummyCreateStringMutationResolver === null) {
            /** @var DummyCreateStringMutationResolver */
            $dummyCreateStringMutationResolver = $this->instanceManager->getInstance(DummyCreateStringMutationResolver::class);
            $this->dummyCreateStringMutationResolver = $dummyCreateStringMutationResolver;
        }
        return $this->dummyCreateStringMutationResolver;
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
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'dummyMutation',
            'dummyReceivingInputObjectWithNestedValidationField',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'dummyMutation' => $this->__('Dummy mutation (nothing really happens, but it returns a String)', 'dummy-schema'),
            'dummyReceivingInputObjectWithNestedValidationField' => $this->__('Dummy field, receiving an Input Object containing other Input Objects, to test their validation is performed', 'dummy-schema'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'dummyMutation' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        return match ($fieldName) {
            'dummyMutation' => $this->getDummyCreateStringMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'dummyMutation' => $this->getStringScalarTypeResolver(),
            'dummyReceivingInputObjectWithNestedValidationField' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'dummyReceivingInputObjectWithNestedValidationField' => [
                'input' => $this->getFirstLayerInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['dummyReceivingInputObjectWithNestedValidationField' => 'input'] => $this->__('First level of Input Object containing other Input Objects, to test their validation is performed', 'dummy-schema'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['dummyReceivingInputObjectWithNestedValidationField' => 'input'] => SchemaTypeModifiers::MANDATORY,
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
            case 'dummyReceivingInputObjectWithNestedValidationField':
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
