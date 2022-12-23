<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\SchemaHooks;

use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostsFilterInputObjectTypeResolverInterface;
use PoPCMSSchema\Tags\SchemaHooks\AbstractAddTagFilterInputObjectTypeHookSet;

class AddTagFilterInputObjectTypeHookSet extends AbstractAddTagFilterInputObjectTypeHookSet
{
    protected function getInputObjectTypeResolverClass(): string
    {
        return PostsFilterInputObjectTypeResolverInterface::class;
    }
}
