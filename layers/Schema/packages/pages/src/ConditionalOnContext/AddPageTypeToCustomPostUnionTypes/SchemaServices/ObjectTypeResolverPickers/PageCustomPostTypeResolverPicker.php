<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ConditionalOnContext\AddPageTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\Pages\ObjectTypeResolverPickers\AbstractPageTypeResolverPicker;

class PageCustomPostTypeResolverPicker extends AbstractPageTypeResolverPicker
{
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
