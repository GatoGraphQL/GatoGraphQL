<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers\ObjectType;

use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\CustomPosts\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;

class CustomPostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }
}
