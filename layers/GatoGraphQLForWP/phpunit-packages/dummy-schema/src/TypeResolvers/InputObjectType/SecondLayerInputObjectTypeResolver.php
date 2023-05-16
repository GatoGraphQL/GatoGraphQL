<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummySchema\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use stdClass;

class SecondLayerInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?ThirdLayerInputObjectTypeResolver $thirdLayerFromInputObjectTypeResolver = null;

    final public function setThirdLayerInputObjectTypeResolver(ThirdLayerInputObjectTypeResolver $thirdLayerFromInputObjectTypeResolver): void
    {
        $this->thirdLayerFromInputObjectTypeResolver = $thirdLayerFromInputObjectTypeResolver;
    }
    final protected function getThirdLayerInputObjectTypeResolver(): ThirdLayerInputObjectTypeResolver
    {
        /** @var ThirdLayerInputObjectTypeResolver */
        return $this->thirdLayerFromInputObjectTypeResolver ??= $this->instanceManager->getInstance(ThirdLayerInputObjectTypeResolver::class);
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

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'inputOn2ndLevel' => [new stdClass()],
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }
}
