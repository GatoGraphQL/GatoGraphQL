<?php

declare(strict_types=1);

namespace PoPSchema\Stances\ConditionalOnContext\AddStanceTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\Stances\TypeResolverPickers\AbstractStanceTypeResolverPicker;

class StanceCustomPostTypeResolverPicker extends AbstractStanceTypeResolverPicker
{
    public function getClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
