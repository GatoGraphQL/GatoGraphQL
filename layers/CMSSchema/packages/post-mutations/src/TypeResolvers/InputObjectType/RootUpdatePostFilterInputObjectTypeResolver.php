<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\TypeResolvers\InputObjectType;

use PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootUpdateCustomPostFilterInputObjectTypeResolver;

class RootUpdatePostFilterInputObjectTypeResolver extends RootUpdateCustomPostFilterInputObjectTypeResolver implements UpdatePostFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdatePostFilterInput';
    }
}
