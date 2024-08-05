<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdatePostCategoryTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdatePostCategoryTermMutationResolver $updatePostCategoryTermMutationResolver = null;

    final public function setUpdatePostCategoryTermMutationResolver(UpdatePostCategoryTermMutationResolver $updatePostCategoryTermMutationResolver): void
    {
        $this->updatePostCategoryTermMutationResolver = $updatePostCategoryTermMutationResolver;
    }
    final protected function getUpdatePostCategoryTermMutationResolver(): UpdatePostCategoryTermMutationResolver
    {
        if ($this->updatePostCategoryTermMutationResolver === null) {
            /** @var UpdatePostCategoryTermMutationResolver */
            $updatePostCategoryTermMutationResolver = $this->instanceManager->getInstance(UpdatePostCategoryTermMutationResolver::class);
            $this->updatePostCategoryTermMutationResolver = $updatePostCategoryTermMutationResolver;
        }
        return $this->updatePostCategoryTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdatePostCategoryTermMutationResolver();
    }
}
