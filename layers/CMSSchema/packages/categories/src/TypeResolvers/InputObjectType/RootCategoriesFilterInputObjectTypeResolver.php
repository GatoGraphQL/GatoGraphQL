<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

class RootCategoriesFilterInputObjectTypeResolver extends AbstractCategoriesFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootCategoriesFilterInput';
    }
}
