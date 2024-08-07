<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class RootUpdateCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver implements UpdateCategoryTermInputObjectTypeResolverInterface
{
    use RootUpdateCategoryTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootUpdateCategoryInput';
    }
}
