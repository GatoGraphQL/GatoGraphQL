<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class SetCategoriesOnCustomPostBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?SetCategoriesOnCustomPostMutationResolver $setCategoriesOnCustomPostMutationResolver = null;

    final protected function getSetCategoriesOnCustomPostMutationResolver(): SetCategoriesOnCustomPostMutationResolver
    {
        if ($this->setCategoriesOnCustomPostMutationResolver === null) {
            /** @var SetCategoriesOnCustomPostMutationResolver */
            $setCategoriesOnCustomPostMutationResolver = $this->instanceManager->getInstance(SetCategoriesOnCustomPostMutationResolver::class);
            $this->setCategoriesOnCustomPostMutationResolver = $setCategoriesOnCustomPostMutationResolver;
        }
        return $this->setCategoriesOnCustomPostMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetCategoriesOnCustomPostMutationResolver();
    }
}
