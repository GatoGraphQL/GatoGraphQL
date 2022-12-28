<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\SchemaHooks;

use PoPCMSSchema\Categories\SchemaHooks\AbstractCustomPostAddCategoryFilterInputObjectTypeHookSet;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\RootCustomPostsFilterInputObjectTypeResolver;

class RootCustomPostsAddCategoryFilterInputObjectTypeHookSet extends AbstractCustomPostAddCategoryFilterInputObjectTypeHookSet
{
    protected function getInputObjectTypeResolverClass(): string
    {
        return RootCustomPostsFilterInputObjectTypeResolver::class;
    }
}
