<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\ConditionalOnContext\AddPageTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPCMSSchema\Pages\ObjectTypeResolverPickers\AbstractPageObjectTypeResolverPicker;

class PageCustomPostObjectTypeResolverPicker extends AbstractPageObjectTypeResolverPicker
{
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
