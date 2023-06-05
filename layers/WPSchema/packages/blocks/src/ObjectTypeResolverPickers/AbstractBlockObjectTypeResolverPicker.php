<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\ObjectTypeResolverPickers;

use PoPWPSchema\Blocks\TypeResolvers\UnionType\BlockUnionTypeResolver;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractTransientObjectObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

abstract class AbstractBlockObjectTypeResolverPicker extends AbstractTransientObjectObjectTypeResolverPicker implements BlockObjectTypeResolverPickerInterface
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    final public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            BlockUnionTypeResolver::class,
        ];
    }
}
