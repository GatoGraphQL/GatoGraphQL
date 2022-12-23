<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\SchemaHooks;

use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\RootCustomPostsFilterInputObjectTypeResolver;

class RootCustomPostsAddTagFilterInputObjectTypeHookSet extends AbstractAddTagFilterInputObjectTypeHookSet
{
    protected function getInputObjectTypeResolverClass(): string
    {
        return RootCustomPostsFilterInputObjectTypeResolver::class;
    }
}
