<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoPWPSchema\Blocks\FilterInputs\BlockTypeAttributeAutoGenerateControlFilterInput;
use PoPWPSchema\Blocks\FilterInputs\BlockTypeAttributeFieldTypeFilterInput;
use PoPWPSchema\Blocks\TypeResolvers\ScalarType\BlockTypeAttributeFieldTypeEnumStringScalarTypeResolver;

class BlockTypeAttributesFilterInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?BlockTypeAttributeFieldTypeEnumStringScalarTypeResolver $blockTypeAttributeFieldTypeEnumStringScalarTypeResolver = null;
    private ?BlockTypeAttributeFieldTypeFilterInput $blockTypeAttributeFieldTypeFilterInput = null;
    private ?BlockTypeAttributeAutoGenerateControlFilterInput $blockTypeAttributeAutoGenerateControlFilterInput = null;

    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final protected function getBlockTypeAttributeFieldTypeEnumStringScalarTypeResolver(): BlockTypeAttributeFieldTypeEnumStringScalarTypeResolver
    {
        if ($this->blockTypeAttributeFieldTypeEnumStringScalarTypeResolver === null) {
            /** @var BlockTypeAttributeFieldTypeEnumStringScalarTypeResolver */
            $blockTypeAttributeFieldTypeEnumStringScalarTypeResolver = $this->instanceManager->getInstance(BlockTypeAttributeFieldTypeEnumStringScalarTypeResolver::class);
            $this->blockTypeAttributeFieldTypeEnumStringScalarTypeResolver = $blockTypeAttributeFieldTypeEnumStringScalarTypeResolver;
        }
        return $this->blockTypeAttributeFieldTypeEnumStringScalarTypeResolver;
    }
    final protected function getBlockTypeAttributeFieldTypeFilterInput(): BlockTypeAttributeFieldTypeFilterInput
    {
        if ($this->blockTypeAttributeFieldTypeFilterInput === null) {
            /** @var BlockTypeAttributeFieldTypeFilterInput */
            $blockTypeAttributeFieldTypeFilterInput = $this->instanceManager->getInstance(BlockTypeAttributeFieldTypeFilterInput::class);
            $this->blockTypeAttributeFieldTypeFilterInput = $blockTypeAttributeFieldTypeFilterInput;
        }
        return $this->blockTypeAttributeFieldTypeFilterInput;
    }
    final protected function getBlockTypeAttributeAutoGenerateControlFilterInput(): BlockTypeAttributeAutoGenerateControlFilterInput
    {
        if ($this->blockTypeAttributeAutoGenerateControlFilterInput === null) {
            /** @var BlockTypeAttributeAutoGenerateControlFilterInput */
            $blockTypeAttributeAutoGenerateControlFilterInput = $this->instanceManager->getInstance(BlockTypeAttributeAutoGenerateControlFilterInput::class);
            $this->blockTypeAttributeAutoGenerateControlFilterInput = $blockTypeAttributeAutoGenerateControlFilterInput;
        }
        return $this->blockTypeAttributeAutoGenerateControlFilterInput;
    }

    public function getTypeName(): string
    {
        return 'BlockTypeAttributesFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter the attributes of a block type', 'blocks');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'fieldType' => $this->getBlockTypeAttributeFieldTypeEnumStringScalarTypeResolver(),
            'autoGenerateControl' => $this->getBooleanScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'fieldType' => $this->__('Filter attributes by their JSON-Schema "type" property', 'blocks'),
            'autoGenerateControl' => $this->__('Filter attributes that have (or do not have) an auto-generated editor control. Available since WordPress 7.0.', 'blocks'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'fieldType' => $this->getBlockTypeAttributeFieldTypeFilterInput(),
            'autoGenerateControl' => $this->getBlockTypeAttributeAutoGenerateControlFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
