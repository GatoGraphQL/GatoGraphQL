<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\TypeResolvers\InputObjectType;

use PoPSchema\Categories\TypeResolvers\InputObjectType\AbstractCategoryByInputObjectTypeResolver;

class PostCategoryByInputObjectTypeResolver extends AbstractCategoryByInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostCategoryByInput';
    }
}
