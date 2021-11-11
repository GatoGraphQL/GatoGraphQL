<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostSortInputObjectTypeResolver;

class PostSortInputObjectTypeResolver extends CustomPostSortInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostSortInput';
    }
}
