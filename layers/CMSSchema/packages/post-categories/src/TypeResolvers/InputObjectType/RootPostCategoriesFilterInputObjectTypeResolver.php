<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\AbstractCategoriesFilterInputObjectTypeResolver;

class RootPostCategoriesFilterInputObjectTypeResolver extends AbstractCategoriesFilterInputObjectTypeResolver implements PostCategoriesFilterInputObjectTypeResolverInterface
{
    public function getTypeName(): string
    {
        return 'RootPostCategoriesFilterInput';
    }
}
