<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummySchema\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use stdClass;

class FirstLayerInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?SecondLayerInputObjectTypeResolver $secondLayerFromInputObjectTypeResolver = null;

    final public function setSecondLayerInputObjectTypeResolver(SecondLayerInputObjectTypeResolver $secondLayerFromInputObjectTypeResolver): void
    {
        $this->secondLayerFromInputObjectTypeResolver = $secondLayerFromInputObjectTypeResolver;
    }
    final protected function getSecondLayerInputObjectTypeResolver(): SecondLayerInputObjectTypeResolver
    {
        /** @var SecondLayerInputObjectTypeResolver */
        return $this->secondLayerFromInputObjectTypeResolver ??= $this->instanceManager->getInstance(SecondLayerInputObjectTypeResolver::class);
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
            'input' => $this->getSecondLayerInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'input' => $this->__('Inner Input Object', 'dummy-schema'),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'input' => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'input' => new stdClass(),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }
}
