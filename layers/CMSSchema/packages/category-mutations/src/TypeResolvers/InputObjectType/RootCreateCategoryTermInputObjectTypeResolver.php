<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

class RootCreateCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver implements CreateCategoryTermInputObjectTypeResolverInterface
{
    use RootCreateCategoryTermInputObjectTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'RootCreateCategoryInput';
    }
}
