<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummySchema\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class ThirdLayerInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?FourthLayerInputObjectTypeResolver $fourthLayerFromInputObjectTypeResolver = null;

    final protected function getFourthLayerInputObjectTypeResolver(): FourthLayerInputObjectTypeResolver
    {
        if ($this->fourthLayerFromInputObjectTypeResolver === null) {
            /** @var FourthLayerInputObjectTypeResolver */
            $fourthLayerFromInputObjectTypeResolver = $this->instanceManager->getInstance(FourthLayerInputObjectTypeResolver::class);
            $this->fourthLayerFromInputObjectTypeResolver = $fourthLayerFromInputObjectTypeResolver;
        }
        return $this->fourthLayerFromInputObjectTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'ThirdLayerInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input Object containing other Input Objects, to test their validation is performed', 'dummy-schema');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'inputOn3rdLevel' => $this->getFourthLayerInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'inputOn3rdLevel' => $this->__('Inner Input Object', 'dummy-schema'),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'inputOn3rdLevel' => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
