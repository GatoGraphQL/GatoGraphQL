<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CustomPostUpdateFilterInputObjectTypeResolver;

class PostUpdateFilterInputObjectTypeResolver extends CustomPostUpdateFilterInputObjectTypeResolver implements UpdatePostFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'PostUpdateFilterInput';
    }
}
