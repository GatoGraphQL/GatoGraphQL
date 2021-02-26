<?php

declare(strict_types=1);

namespace PoPSchema\LocationPosts\ConditionalOnEnvironment\AddLocationPostTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\LocationPosts\TypeResolverPickers\AbstractLocationPostTypeResolverPicker;

class LocationPostCustomPostTypeResolverPicker extends AbstractLocationPostTypeResolverPicker
{
    public function getClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
