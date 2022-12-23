<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\SchemaHooks;

use PoPCMSSchema\Categories\SchemaHooks\AbstractAddCategoryFilterInputObjectTypeHookSet;
use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostsFilterInputObjectTypeResolverInterface;

class AddCategoryFilterInputObjectTypeHookSet extends AbstractAddCategoryFilterInputObjectTypeHookSet
{
    protected function getInputObjectTypeResolverClass(): string
    {
        return PostsFilterInputObjectTypeResolverInterface::class;
    }
}
