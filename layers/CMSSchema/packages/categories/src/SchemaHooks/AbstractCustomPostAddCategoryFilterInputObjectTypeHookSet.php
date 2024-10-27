<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\SchemaHooks;

use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\CustomPostsFilterCustomPostsByCategoriesInputObjectTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\FilterCustomPostsByCategoriesInputObjectTypeResolverInterface;

abstract class AbstractCustomPostAddCategoryFilterInputObjectTypeHookSet extends AbstractAddCategoryFilterInputObjectTypeHookSet
{
    private ?CustomPostsFilterCustomPostsByCategoriesInputObjectTypeResolver $customPostsFilterCustomPostsByCategoriesInputObjectTypeResolver = null;

    final protected function getCustomPostsFilterCustomPostsByCategoriesInputObjectTypeResolver(): CustomPostsFilterCustomPostsByCategoriesInputObjectTypeResolver
    {
        if ($this->customPostsFilterCustomPostsByCategoriesInputObjectTypeResolver === null) {
            /** @var CustomPostsFilterCustomPostsByCategoriesInputObjectTypeResolver */
            $customPostsFilterCustomPostsByCategoriesInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostsFilterCustomPostsByCategoriesInputObjectTypeResolver::class);
            $this->customPostsFilterCustomPostsByCategoriesInputObjectTypeResolver = $customPostsFilterCustomPostsByCategoriesInputObjectTypeResolver;
        }
        return $this->customPostsFilterCustomPostsByCategoriesInputObjectTypeResolver;
    }

    protected function getFilterCustomPostsByCategoriesInputObjectTypeResolver(): FilterCustomPostsByCategoriesInputObjectTypeResolverInterface
    {
        return $this->getCustomPostsFilterCustomPostsByCategoriesInputObjectTypeResolver();
    }
}
