<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummySchema\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use stdClass;

class ThirdLayerInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?FourthLayerInputObjectTypeResolver $fourthLayerFromInputObjectTypeResolver = null;

    final public function setFourthLayerInputObjectTypeResolver(FourthLayerInputObjectTypeResolver $fourthLayerFromInputObjectTypeResolver): void
    {
        $this->fourthLayerFromInputObjectTypeResolver = $fourthLayerFromInputObjectTypeResolver;
    }
    final protected function getFourthLayerInputObjectTypeResolver(): FourthLayerInputObjectTypeResolver
    {
        /** @var FourthLayerInputObjectTypeResolver */
        return $this->fourthLayerFromInputObjectTypeResolver ??= $this->instanceManager->getInstance(FourthLayerInputObjectTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'ThirdLayerInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input Object containing another Input Objects, to test their validation is performed', 'dummy-schema');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'innerLayer' => $this->getFourthLayerInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'innerLayer' => $this->__('Inner Input Object', 'dummy-schema'),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'innerLayer' => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'innerLayer' => new stdClass(),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }
}
