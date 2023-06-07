<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;

class BlockFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?BlockFilterByInputObjectTypeResolver $blockFilterByInputObjectTypeResolver = null;

    final public function setBlockFilterByInputObjectTypeResolver(BlockFilterByInputObjectTypeResolver $blockFilterByInputObjectTypeResolver): void
    {
        $this->blockFilterByInputObjectTypeResolver = $blockFilterByInputObjectTypeResolver;
    }
    final protected function getBlockFilterByInputObjectTypeResolver(): BlockFilterByInputObjectTypeResolver
    {
        /** @var BlockFilterByInputObjectTypeResolver */
        return $this->blockFilterByInputObjectTypeResolver ??= $this->instanceManager->getInstance(BlockFilterByInputObjectTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'BlockFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter blocks', 'blocks');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'filter' => $this->getBlockFilterByInputObjectTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'filter' => $this->__('Filter blocks', 'blocks'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
