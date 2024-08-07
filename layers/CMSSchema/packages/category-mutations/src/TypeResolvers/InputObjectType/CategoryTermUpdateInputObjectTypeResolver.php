<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class CategoryTermUpdateInputObjectTypeResolver extends AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver implements UpdateCategoryTermInputObjectTypeResolverInterface
{
    use CategoryTermUpdateInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'CategoryUpdateInput';
    }
}
