<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummySchema\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class FirstLayerInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?SecondLayerInputObjectTypeResolver $secondLayerFromInputObjectTypeResolver = null;

    final protected function getSecondLayerInputObjectTypeResolver(): SecondLayerInputObjectTypeResolver
    {
        if ($this->secondLayerFromInputObjectTypeResolver === null) {
            /** @var SecondLayerInputObjectTypeResolver */
            $secondLayerFromInputObjectTypeResolver = $this->instanceManager->getInstance(SecondLayerInputObjectTypeResolver::class);
            $this->secondLayerFromInputObjectTypeResolver = $secondLayerFromInputObjectTypeResolver;
        }
        return $this->secondLayerFromInputObjectTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'FirstLayerInput';
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
            'inputOn1stLevel' => $this->getSecondLayerInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'inputOn1stLevel' => $this->__('Inner Input Object', 'dummy-schema'),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'inputOn1stLevel' => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
