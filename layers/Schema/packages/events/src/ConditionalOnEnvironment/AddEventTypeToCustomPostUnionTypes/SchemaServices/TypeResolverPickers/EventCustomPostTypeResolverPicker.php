<?php

declare(strict_types=1);

namespace PoPSchema\Events\ConditionalOnEnvironment\AddEventTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\Events\TypeResolverPickers\AbstractEventTypeResolverPicker;

class EventCustomPostTypeResolverPicker extends AbstractEventTypeResolverPicker
{
    public function getClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
    }
}
