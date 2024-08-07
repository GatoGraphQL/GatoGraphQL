<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\CategoryTermUpdateInputObjectTypeResolverTrait;

class PostCategoryTermUpdateInputObjectTypeResolver extends AbstractCreateOrUpdatePostCategoryTermInputObjectTypeResolver implements UpdatePostCategoryTermInputObjectTypeResolverInterface
{
    use CategoryTermUpdateInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'PostCategoryUpdateInput';
    }
}
