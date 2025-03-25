<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

class RootDeleteGenericCategoryTermMetaInputObjectTypeResolver extends AbstractDeleteGenericCategoryTermMetaInputObjectTypeResolver implements DeleteGenericCategoryTermMetaInputObjectTypeResolverInterface
{
    use RootDeleteCategoryTermMetaInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootDeleteGenericCategoryInput';
    }
}
