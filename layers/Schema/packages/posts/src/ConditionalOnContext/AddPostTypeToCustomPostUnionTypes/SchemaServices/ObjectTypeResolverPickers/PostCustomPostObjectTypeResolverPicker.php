<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ConditionalOnContext\AddPostTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\Posts\ObjectTypeResolverPickers\AbstractPostObjectTypeResolverPicker;

class PostCustomPostObjectTypeResolverPicker extends AbstractPostObjectTypeResolverPicker
{
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
