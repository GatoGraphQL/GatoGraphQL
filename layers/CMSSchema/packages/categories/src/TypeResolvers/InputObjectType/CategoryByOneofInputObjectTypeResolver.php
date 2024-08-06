<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\AbstractCategoryByOneofInputObjectTypeResolver;

class CategoryByOneofInputObjectTypeResolver extends AbstractCategoryByOneofInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CategoryByFilterInput';
    }
}
