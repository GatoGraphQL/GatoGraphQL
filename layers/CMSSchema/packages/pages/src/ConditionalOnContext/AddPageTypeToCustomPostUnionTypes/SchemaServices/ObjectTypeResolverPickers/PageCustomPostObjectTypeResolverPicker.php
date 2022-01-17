<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ConditionalOnContext\AddPageTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\Pages\ObjectTypeResolverPickers\AbstractPageObjectTypeResolverPicker;

class PageCustomPostObjectTypeResolverPicker extends AbstractPageObjectTypeResolverPicker
{
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
