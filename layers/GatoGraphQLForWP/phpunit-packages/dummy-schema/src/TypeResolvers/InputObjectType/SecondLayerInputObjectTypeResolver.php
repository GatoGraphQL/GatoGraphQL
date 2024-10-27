<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummySchema\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class SecondLayerInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?ThirdLayerInputObjectTypeResolver $thirdLayerFromInputObjectTypeResolver = null;

    final protected function getThirdLayerInputObjectTypeResolver(): ThirdLayerInputObjectTypeResolver
    {
        if ($this->thirdLayerFromInputObjectTypeResolver === null) {
            /** @var ThirdLayerInputObjectTypeResolver */
            $thirdLayerFromInputObjectTypeResolver = $this->instanceManager->getInstance(ThirdLayerInputObjectTypeResolver::class);
            $this->thirdLayerFromInputObjectTypeResolver = $thirdLayerFromInputObjectTypeResolver;
        }
        return $this->thirdLayerFromInputObjectTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'SecondLayerInput';
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
            'inputOn2ndLevel' => $this->getThirdLayerInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'inputOn2ndLevel' => $this->__('Inner Input Object', 'dummy-schema'),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'inputOn2ndLevel' => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
