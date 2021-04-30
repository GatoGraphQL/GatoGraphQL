<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\ConditionalOnContext\AddHighlightTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\Highlights\TypeResolverPickers\AbstractHighlightTypeResolverPicker;

class HighlightCustomPostTypeResolverPicker extends AbstractHighlightTypeResolverPicker
{
    public function getClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
