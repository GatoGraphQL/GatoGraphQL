<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeleteCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeleteCategoryTermMetaMutationResolver $deleteCategoryTermMetaMutationResolver = null;

    final protected function getDeleteCategoryTermMetaMutationResolver(): DeleteCategoryTermMetaMutationResolver
    {
        if ($this->deleteCategoryTermMetaMutationResolver === null) {
            /** @var DeleteCategoryTermMetaMutationResolver */
            $deleteCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(DeleteCategoryTermMetaMutationResolver::class);
            $this->deleteCategoryTermMetaMutationResolver = $deleteCategoryTermMetaMutationResolver;
        }
        return $this->deleteCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeleteCategoryTermMetaMutationResolver();
    }
}
