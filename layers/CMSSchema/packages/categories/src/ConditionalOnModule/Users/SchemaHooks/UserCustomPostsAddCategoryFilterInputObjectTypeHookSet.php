<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ConditionalOnModule\Users\SchemaHooks;

use PoPCMSSchema\Categories\SchemaHooks\AbstractCustomPostAddCategoryFilterInputObjectTypeHookSet;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeResolvers\InputObjectType\UserCustomPostsFilterInputObjectTypeResolver;

class UserCustomPostsAddCategoryFilterInputObjectTypeHookSet extends AbstractCustomPostAddCategoryFilterInputObjectTypeHookSet
{
    protected function getInputObjectTypeResolverClass(): string
    {
        return UserCustomPostsFilterInputObjectTypeResolver::class;
    }
}
