<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ConditionalOnContext\AddPostTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\Posts\TypeResolverPickers\AbstractPostTypeResolverPicker;

class PostCustomPostTypeResolverPicker extends AbstractPostTypeResolverPicker
{
    public function getClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
