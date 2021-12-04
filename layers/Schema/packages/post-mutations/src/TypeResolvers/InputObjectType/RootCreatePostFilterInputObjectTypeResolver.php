<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\TypeResolvers\InputObjectType;

use PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootCreateCustomPostFilterInputObjectTypeResolver;

class RootCreatePostFilterInputObjectTypeResolver extends RootCreateCustomPostFilterInputObjectTypeResolver implements CreatePostFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootCreatePostFilterInput';
    }
}
