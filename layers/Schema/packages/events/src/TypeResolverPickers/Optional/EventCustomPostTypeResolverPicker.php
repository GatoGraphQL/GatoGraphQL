<?php

declare(strict_types=1);

namespace PoPSchema\Events\TypeResolverPickers\Optional;

use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\Events\TypeResolverPickers\AbstractEventTypeResolverPicker;

class EventCustomPostTypeResolverPicker extends AbstractEventTypeResolverPicker
{
    public static function getClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
