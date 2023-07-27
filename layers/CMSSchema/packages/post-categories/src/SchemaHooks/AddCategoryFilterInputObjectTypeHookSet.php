<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\SchemaHooks;

use PoPCMSSchema\Categories\SchemaHooks\AbstractAddCategoryFilterInputObjectTypeHookSet;
use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\FilterCustomPostsByCategoriesInputObjectTypeResolverInterface;
use PoPCMSSchema\PostCategories\TypeResolvers\InputObjectType\PostsFilterCustomPostsByCategoriesInputObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostsFilterInputObjectTypeResolverInterface;

class AddCategoryFilterInputObjectTypeHookSet extends AbstractAddCategoryFilterInputObjectTypeHookSet
{
    private ?PostsFilterCustomPostsByCategoriesInputObjectTypeResolver $postsFilterCustomPostsByCategoriesInputObjectTypeResolver = null;

    final public function setPostsFilterCustomPostsByCategoriesInputObjectTypeResolver(PostsFilterCustomPostsByCategoriesInputObjectTypeResolver $postsFilterCustomPostsByCategoriesInputObjectTypeResolver): void
    {
        $this->postsFilterCustomPostsByCategoriesInputObjectTypeResolver = $postsFilterCustomPostsByCategoriesInputObjectTypeResolver;
    }
    final protected function getPostsFilterCustomPostsByCategoriesInputObjectTypeResolver(): PostsFilterCustomPostsByCategoriesInputObjectTypeResolver
    {
        if ($this->postsFilterCustomPostsByCategoriesInputObjectTypeResolver === null) {
            /** @var PostsFilterCustomPostsByCategoriesInputObjectTypeResolver */
            $postsFilterCustomPostsByCategoriesInputObjectTypeResolver = $this->instanceManager->getInstance(PostsFilterCustomPostsByCategoriesInputObjectTypeResolver::class);
            $this->postsFilterCustomPostsByCategoriesInputObjectTypeResolver = $postsFilterCustomPostsByCategoriesInputObjectTypeResolver;
        }
        return $this->postsFilterCustomPostsByCategoriesInputObjectTypeResolver;
    }

    protected function getInputObjectTypeResolverClass(): string
    {
        return PostsFilterInputObjectTypeResolverInterface::class;
    }

    protected function getFilterCustomPostsByCategoriesInputObjectTypeResolver(): FilterCustomPostsByCategoriesInputObjectTypeResolverInterface
    {
        return $this->getPostsFilterCustomPostsByCategoriesInputObjectTypeResolver();
    }
}
