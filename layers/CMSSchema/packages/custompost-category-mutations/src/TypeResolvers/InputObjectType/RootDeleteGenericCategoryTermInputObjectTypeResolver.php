<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\RootDeleteCategoryTermInputObjectTypeResolverTrait;

class RootDeleteGenericCategoryTermInputObjectTypeResolver extends AbstractDeleteGenericCategoryTermInputObjectTypeResolver implements DeleteGenericCategoryTermInputObjectTypeResolverInterface
{
    use RootDeleteCategoryTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootDeleteGenericCategoryInput';
    }
}
