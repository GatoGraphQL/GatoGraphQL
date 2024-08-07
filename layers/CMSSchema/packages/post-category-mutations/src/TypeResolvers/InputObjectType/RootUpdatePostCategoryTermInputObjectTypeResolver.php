<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\RootUpdateCategoryTermInputObjectTypeResolverTrait;

class RootUpdatePostCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdatePostCategoryTermInputObjectTypeResolver implements UpdatePostCategoryTermInputObjectTypeResolverInterface
{
    use RootUpdateCategoryTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootUpdatePostCategoryInput';
    }
}
