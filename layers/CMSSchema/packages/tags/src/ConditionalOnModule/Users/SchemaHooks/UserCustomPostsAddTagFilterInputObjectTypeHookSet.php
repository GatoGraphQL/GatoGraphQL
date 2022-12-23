<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ConditionalOnModule\Users\SchemaHooks;

use PoPCMSSchema\Tags\SchemaHooks\AbstractAddTagFilterInputObjectTypeHookSet;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeResolvers\InputObjectType\UserCustomPostsFilterInputObjectTypeResolver;

class UserCustomPostsAddTagFilterInputObjectTypeHookSet extends AbstractAddTagFilterInputObjectTypeHookSet
{
    protected function getInputObjectTypeResolverClass(): string
    {
        return UserCustomPostsFilterInputObjectTypeResolver::class;
    }
}
