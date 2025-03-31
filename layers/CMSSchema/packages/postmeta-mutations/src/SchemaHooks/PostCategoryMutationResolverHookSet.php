<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\SchemaHooks;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\CategoryMetaMutations\SchemaHooks\AbstractCategoryMutationResolverHookSet;

class PostMutationResolverHookSet extends AbstractCategoryMutationResolverHookSet
{
    use PostMutationResolverHookSetTrait;

    private ?PostObjectTypeResolver $postCategoryObjectTypeResolver = null;

    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postCategoryObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postCategoryObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        }
        return $this->postCategoryObjectTypeResolver;
    }

    protected function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->getPostObjectTypeResolver();
    }
}
