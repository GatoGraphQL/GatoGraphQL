<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ConditionalOnModule\Users\SchemaHooks;

use PoPCMSSchema\Categories\SchemaHooks\AbstractAddCategoryFilterInputObjectTypeHookSet;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeResolvers\InputObjectType\UserCustomPostsFilterInputObjectTypeResolver;

class UserCustomPostsAddCategoryFilterInputObjectTypeHookSet extends AbstractAddCategoryFilterInputObjectTypeHookSet
{
    protected function getInputObjectTypeResolverClass(): string
    {
        return UserCustomPostsFilterInputObjectTypeResolver::class;
    }
}
