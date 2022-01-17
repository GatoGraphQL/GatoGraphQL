<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\TypeResolvers\InputObjectType;

use PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType\CustomPostUpdateFilterInputObjectTypeResolver;

class PostUpdateFilterInputObjectTypeResolver extends CustomPostUpdateFilterInputObjectTypeResolver implements UpdatePostFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'PostUpdateFilterInput';
    }
}
