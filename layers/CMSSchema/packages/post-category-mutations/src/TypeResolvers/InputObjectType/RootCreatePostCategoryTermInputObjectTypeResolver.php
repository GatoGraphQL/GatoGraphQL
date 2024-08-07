<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\RootCreateCategoryTermInputObjectTypeResolverTrait;

class RootCreatePostCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdatePostCategoryTermInputObjectTypeResolver implements CreatePostCategoryTermInputObjectTypeResolverInterface
{
    use RootCreateCategoryTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootCreatePostCategoryInput';
    }
}
