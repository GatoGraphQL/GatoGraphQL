<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\AbstractCategoryByInputObjectTypeResolver;

class PostCategoryByInputObjectTypeResolver extends AbstractCategoryByInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostCategoryByInput';
    }
}
