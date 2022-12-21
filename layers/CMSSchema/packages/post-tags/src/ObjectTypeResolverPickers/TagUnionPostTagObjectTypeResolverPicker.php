<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\ObjectTypeResolverPickers;

use PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class TagUnionPostTagObjectTypeResolverPicker extends AbstractPostTagObjectTypeResolverPicker
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
