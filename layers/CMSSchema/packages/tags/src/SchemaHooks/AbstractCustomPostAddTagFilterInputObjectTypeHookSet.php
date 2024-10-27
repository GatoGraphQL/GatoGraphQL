<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\SchemaHooks;

use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\CustomPostsFilterCustomPostsByTagsInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\FilterCustomPostsByTagsInputObjectTypeResolverInterface;

abstract class AbstractCustomPostAddTagFilterInputObjectTypeHookSet extends AbstractAddTagFilterInputObjectTypeHookSet
{
    private ?CustomPostsFilterCustomPostsByTagsInputObjectTypeResolver $customPostsFilterCustomPostsByTagsInputObjectTypeResolver = null;

    final protected function getCustomPostsFilterCustomPostsByTagsInputObjectTypeResolver(): CustomPostsFilterCustomPostsByTagsInputObjectTypeResolver
    {
        if ($this->customPostsFilterCustomPostsByTagsInputObjectTypeResolver === null) {
            /** @var CustomPostsFilterCustomPostsByTagsInputObjectTypeResolver */
            $customPostsFilterCustomPostsByTagsInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostsFilterCustomPostsByTagsInputObjectTypeResolver::class);
            $this->customPostsFilterCustomPostsByTagsInputObjectTypeResolver = $customPostsFilterCustomPostsByTagsInputObjectTypeResolver;
        }
        return $this->customPostsFilterCustomPostsByTagsInputObjectTypeResolver;
    }

    protected function getFilterCustomPostsByTagsInputObjectTypeResolver(): FilterCustomPostsByTagsInputObjectTypeResolverInterface
    {
        return $this->getCustomPostsFilterCustomPostsByTagsInputObjectTypeResolver();
    }
}
