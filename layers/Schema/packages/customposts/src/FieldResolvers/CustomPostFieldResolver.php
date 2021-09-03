<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers;

use PoPSchema\CustomPosts\TypeResolvers\Object\AbstractCustomPostTypeResolver;
use PoPSchema\CustomPosts\FieldResolvers\AbstractCustomPostFieldResolver;

class CustomPostFieldResolver extends AbstractCustomPostFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostTypeResolver::class,
        ];
    }
}
