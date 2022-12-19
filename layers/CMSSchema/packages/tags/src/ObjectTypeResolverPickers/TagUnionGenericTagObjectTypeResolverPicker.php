<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ObjectTypeResolverPickers;

use PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TagUnionGenericTagObjectTypeResolverPicker extends AbstractGenericTagObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            TagUnionTypeResolver::class,
        ];
    }
}
