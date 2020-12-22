<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers;

use PoPSchema\CustomPosts\TypeResolvers\AbstractCustomPostTypeResolver;
use PoPSchema\CustomPosts\FieldResolvers\AbstractCustomPostFieldResolver;

class CustomPostFieldResolver extends AbstractCustomPostFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostTypeResolver::class,
        ];
    }
}
