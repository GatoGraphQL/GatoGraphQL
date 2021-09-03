<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\ConditionalOnContext\AddHighlightTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\Union\CustomPostUnionTypeResolver;
use PoPSchema\Highlights\ObjectTypeResolverPickers\AbstractHighlightTypeResolverPicker;

class HighlightCustomPostTypeResolverPicker extends AbstractHighlightTypeResolverPicker
{
    public function getClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
