<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\RootDeleteCategoryTermInputObjectTypeResolverTrait;

class RootDeletePostCategoryTermInputObjectTypeResolver extends AbstractDeletePostCategoryTermInputObjectTypeResolver implements DeletePostCategoryTermInputObjectTypeResolverInterface
{
    use RootDeleteCategoryTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootDeletePostCategoryInput';
    }
}
