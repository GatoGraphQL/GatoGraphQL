<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\ObjectTypeResolverPickers;

use PoPWPSchema\Blocks\ObjectModels\GeneralBlock;
use PoPWPSchema\Blocks\TypeResolvers\ObjectType\GeneralBlockObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GeneralBlockObjectTypeResolverPicker extends AbstractBlockObjectTypeResolverPicker
{
    private ?GeneralBlockObjectTypeResolver $generalBlockObjectTypeResolver = null;

    final public function setGeneralBlockObjectTypeResolver(GeneralBlockObjectTypeResolver $generalBlockObjectTypeResolver): void
    {
        $this->generalBlockObjectTypeResolver = $generalBlockObjectTypeResolver;
    }
    final protected function getGeneralBlockObjectTypeResolver(): GeneralBlockObjectTypeResolver
    {
        /** @var GeneralBlockObjectTypeResolver */
        return $this->generalBlockObjectTypeResolver ??= $this->instanceManager->getInstance(GeneralBlockObjectTypeResolver::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getGeneralBlockObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return GeneralBlock::class;
    }
}
