<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ConditionalOnModule\Users\SchemaHooks;

use PoPCMSSchema\Tags\SchemaHooks\AbstractCustomPostAddTagFilterInputObjectTypeHookSet;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeResolvers\InputObjectType\UserCustomPostsFilterInputObjectTypeResolver;

class UserCustomPostsAddTagFilterInputObjectTypeHookSet extends AbstractCustomPostAddTagFilterInputObjectTypeHookSet
{
    protected function getInputObjectTypeResolverClass(): string
    {
        return UserCustomPostsFilterInputObjectTypeResolver::class;
    }
}
