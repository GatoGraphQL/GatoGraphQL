<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class RootDeleteCategoryTermInputObjectTypeResolver extends AbstractDeleteCategoryTermInputObjectTypeResolver implements DeleteCategoryTermInputObjectTypeResolverInterface
{
    use RootDeleteCategoryTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootDeleteCategoryInput';
    }
}
