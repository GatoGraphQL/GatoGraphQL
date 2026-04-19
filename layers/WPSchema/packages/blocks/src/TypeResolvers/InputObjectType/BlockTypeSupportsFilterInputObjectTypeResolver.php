<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;

/**
 * Filter blocks by their `supports` configuration.
 *
 * Each input field is OPTIONAL; when provided (true/false), the matching
 * `$blockType->supports[<key>]` is required to equal-match it.
 *
 * Only the most common supports keys are exposed as typed booleans;
 * for arbitrary keys, use the JSON `supports` field directly on `BlockType`.
 */
class BlockTypeSupportsFilterInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'BlockTypeSupportsFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Filter block types by their `supports` configuration', 'blocks');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'autoRegister' => $this->getBooleanScalarTypeResolver(),
            'anchor' => $this->getBooleanScalarTypeResolver(),
            'align' => $this->getBooleanScalarTypeResolver(),
            'className' => $this->getBooleanScalarTypeResolver(),
            'customClassName' => $this->getBooleanScalarTypeResolver(),
            'html' => $this->getBooleanScalarTypeResolver(),
            'inserter' => $this->getBooleanScalarTypeResolver(),
            'multiple' => $this->getBooleanScalarTypeResolver(),
            'reusable' => $this->getBooleanScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'autoRegister' => $this->__('Filter blocks registered fully in PHP (PHP-only blocks). Available since WordPress 7.0.', 'blocks'),
            'anchor' => $this->__('Filter blocks supporting the `anchor` attribute', 'blocks'),
            'align' => $this->__('Filter blocks supporting alignment', 'blocks'),
            'className' => $this->__('Filter blocks supporting the auto-generated className', 'blocks'),
            'customClassName' => $this->__('Filter blocks supporting a custom className', 'blocks'),
            'html' => $this->__('Filter blocks supporting direct HTML editing', 'blocks'),
            'inserter' => $this->__('Filter blocks shown in the inserter', 'blocks'),
            'multiple' => $this->__('Filter blocks that may be inserted multiple times in a post', 'blocks'),
            'reusable' => $this->__('Filter blocks that can be converted into reusable blocks', 'blocks'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
