<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPWPSchema\Blocks\FilterInputs\BlockTypeExcludeNamesFilterInput;
use PoPWPSchema\Blocks\FilterInputs\BlockTypeHasRenderCallbackFilterInput;
use PoPWPSchema\Blocks\FilterInputs\BlockTypeNameSearchFilterInput;
use PoPWPSchema\Blocks\FilterInputs\BlockTypeNamesFilterInput;
use PoPWPSchema\Blocks\FilterInputs\BlockTypeSupportsFilterInput;

class RootBlockTypesFilterInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?BlockTypeSupportsFilterInputObjectTypeResolver $blockTypeSupportsFilterInputObjectTypeResolver = null;
    private ?BlockTypeNamesFilterInput $blockTypeNamesFilterInput = null;
    private ?BlockTypeExcludeNamesFilterInput $blockTypeExcludeNamesFilterInput = null;
    private ?BlockTypeNameSearchFilterInput $blockTypeNameSearchFilterInput = null;
    private ?BlockTypeSupportsFilterInput $blockTypeSupportsFilterInput = null;
    private ?BlockTypeHasRenderCallbackFilterInput $blockTypeHasRenderCallbackFilterInput = null;

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
    final protected function getBlockTypeSupportsFilterInputObjectTypeResolver(): BlockTypeSupportsFilterInputObjectTypeResolver
    {
        if ($this->blockTypeSupportsFilterInputObjectTypeResolver === null) {
            /** @var BlockTypeSupportsFilterInputObjectTypeResolver */
            $blockTypeSupportsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(BlockTypeSupportsFilterInputObjectTypeResolver::class);
            $this->blockTypeSupportsFilterInputObjectTypeResolver = $blockTypeSupportsFilterInputObjectTypeResolver;
        }
        return $this->blockTypeSupportsFilterInputObjectTypeResolver;
    }
    final protected function getBlockTypeNamesFilterInput(): BlockTypeNamesFilterInput
    {
        if ($this->blockTypeNamesFilterInput === null) {
            /** @var BlockTypeNamesFilterInput */
            $blockTypeNamesFilterInput = $this->instanceManager->getInstance(BlockTypeNamesFilterInput::class);
            $this->blockTypeNamesFilterInput = $blockTypeNamesFilterInput;
        }
        return $this->blockTypeNamesFilterInput;
    }
    final protected function getBlockTypeExcludeNamesFilterInput(): BlockTypeExcludeNamesFilterInput
    {
        if ($this->blockTypeExcludeNamesFilterInput === null) {
            /** @var BlockTypeExcludeNamesFilterInput */
            $blockTypeExcludeNamesFilterInput = $this->instanceManager->getInstance(BlockTypeExcludeNamesFilterInput::class);
            $this->blockTypeExcludeNamesFilterInput = $blockTypeExcludeNamesFilterInput;
        }
        return $this->blockTypeExcludeNamesFilterInput;
    }
    final protected function getBlockTypeNameSearchFilterInput(): BlockTypeNameSearchFilterInput
    {
        if ($this->blockTypeNameSearchFilterInput === null) {
            /** @var BlockTypeNameSearchFilterInput */
            $blockTypeNameSearchFilterInput = $this->instanceManager->getInstance(BlockTypeNameSearchFilterInput::class);
            $this->blockTypeNameSearchFilterInput = $blockTypeNameSearchFilterInput;
        }
        return $this->blockTypeNameSearchFilterInput;
    }
    final protected function getBlockTypeSupportsFilterInput(): BlockTypeSupportsFilterInput
    {
        if ($this->blockTypeSupportsFilterInput === null) {
            /** @var BlockTypeSupportsFilterInput */
            $blockTypeSupportsFilterInput = $this->instanceManager->getInstance(BlockTypeSupportsFilterInput::class);
            $this->blockTypeSupportsFilterInput = $blockTypeSupportsFilterInput;
        }
        return $this->blockTypeSupportsFilterInput;
    }
    final protected function getBlockTypeHasRenderCallbackFilterInput(): BlockTypeHasRenderCallbackFilterInput
    {
        if ($this->blockTypeHasRenderCallbackFilterInput === null) {
            /** @var BlockTypeHasRenderCallbackFilterInput */
            $blockTypeHasRenderCallbackFilterInput = $this->instanceManager->getInstance(BlockTypeHasRenderCallbackFilterInput::class);
            $this->blockTypeHasRenderCallbackFilterInput = $blockTypeHasRenderCallbackFilterInput;
        }
        return $this->blockTypeHasRenderCallbackFilterInput;
    }

    public function getTypeName(): string
    {
        return 'RootBlockTypesFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter the registered block types', 'blocks');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'names' => $this->getStringScalarTypeResolver(),
            'excludeNames' => $this->getStringScalarTypeResolver(),
            'nameSearch' => $this->getStringScalarTypeResolver(),
            'supports' => $this->getBlockTypeSupportsFilterInputObjectTypeResolver(),
            'hasRenderCallback' => $this->getBooleanScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'names' => $this->__('Limit results to block types with these names (e.g. "core/paragraph")', 'blocks'),
            'excludeNames' => $this->__('Exclude block types with these names', 'blocks'),
            'nameSearch' => $this->__('Filter block types whose name contains the given substring', 'blocks'),
            'supports' => $this->__('Filter by `supports` configuration. e.g. `{ autoRegister: true }` returns PHP-only blocks (WP 7.0+)', 'blocks'),
            'hasRenderCallback' => $this->__('Filter blocks that do (or do not) have a registered `render_callback`', 'blocks'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'names',
            'excludeNames'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'names' => $this->getBlockTypeNamesFilterInput(),
            'excludeNames' => $this->getBlockTypeExcludeNamesFilterInput(),
            'nameSearch' => $this->getBlockTypeNameSearchFilterInput(),
            'supports' => $this->getBlockTypeSupportsFilterInput(),
            'hasRenderCallback' => $this->getBlockTypeHasRenderCallbackFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
