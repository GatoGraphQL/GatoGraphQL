<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ConditionalOnContext\AddPostTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\Posts\ObjectTypeResolverPickers\AbstractPostTypeResolverPicker;

class PostCustomPostTypeResolverPicker extends AbstractPostTypeResolverPicker
{
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
