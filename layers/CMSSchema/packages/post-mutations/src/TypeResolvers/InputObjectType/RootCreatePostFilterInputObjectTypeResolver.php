<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootCreateCustomPostFilterInputObjectTypeResolver;

class RootCreatePostFilterInputObjectTypeResolver extends RootCreateCustomPostFilterInputObjectTypeResolver implements CreatePostFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootCreatePostFilterInput';
    }
}
