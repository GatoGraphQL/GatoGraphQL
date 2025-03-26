<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\InputObjectType\AbstractDeleteCategoryTermMetaInputObjectTypeResolver;

class RootDeleteGenericCategoryTermMetaInputObjectTypeResolver extends AbstractDeleteCategoryTermMetaInputObjectTypeResolver implements DeleteGenericCategoryTermMetaInputObjectTypeResolverInterface
{
    use RootDeleteCategoryTermMetaInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootDeleteGenericCategoryInput';
    }
}
