<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\ConditionalOnContext\AddHighlightTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\Highlights\ObjectTypeResolverPickers\AbstractHighlightObjectTypeResolverPicker;

class HighlightCustomPostObjectTypeResolverPicker extends AbstractHighlightObjectTypeResolverPicker
{
    /**
     * @return array<class-string<\PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
