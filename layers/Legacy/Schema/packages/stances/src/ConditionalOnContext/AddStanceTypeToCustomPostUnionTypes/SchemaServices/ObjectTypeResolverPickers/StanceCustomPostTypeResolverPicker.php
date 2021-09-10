<?php

declare(strict_types=1);

namespace PoPSchema\Stances\ConditionalOnContext\AddStanceTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\Union\CustomPostUnionTypeResolver;
use PoPSchema\Stances\ObjectTypeResolverPickers\AbstractStanceTypeResolverPicker;

class StanceCustomPostTypeResolverPicker extends AbstractStanceTypeResolverPicker
{
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
