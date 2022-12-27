<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\AbstractCategoryByInputObjectTypeResolver;

class CategoryByInputObjectTypeResolver extends AbstractCategoryByInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CategoryByInput';
    }
}
