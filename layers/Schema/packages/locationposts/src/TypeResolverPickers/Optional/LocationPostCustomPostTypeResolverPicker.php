<?php

declare(strict_types=1);

namespace PoPSchema\LocationPosts\TypeResolverPickers\Optional;

use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\LocationPosts\TypeResolverPickers\AbstractLocationPostTypeResolverPicker;

class LocationPostCustomPostTypeResolverPicker extends AbstractLocationPostTypeResolverPicker
{
    public static function getClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
