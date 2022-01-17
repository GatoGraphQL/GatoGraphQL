<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootUpdateCustomPostFilterInputObjectTypeResolver;

class RootUpdatePostFilterInputObjectTypeResolver extends RootUpdateCustomPostFilterInputObjectTypeResolver implements UpdatePostFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootUpdatePostFilterInput';
    }
}
