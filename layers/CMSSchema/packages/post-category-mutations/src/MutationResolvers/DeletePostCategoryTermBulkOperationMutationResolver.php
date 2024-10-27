<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeletePostCategoryTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeletePostCategoryTermMutationResolver $deletePostCategoryTermMutationResolver = null;

    final protected function getDeletePostCategoryTermMutationResolver(): DeletePostCategoryTermMutationResolver
    {
        if ($this->deletePostCategoryTermMutationResolver === null) {
            /** @var DeletePostCategoryTermMutationResolver */
            $deletePostCategoryTermMutationResolver = $this->instanceManager->getInstance(DeletePostCategoryTermMutationResolver::class);
            $this->deletePostCategoryTermMutationResolver = $deletePostCategoryTermMutationResolver;
        }
        return $this->deletePostCategoryTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeletePostCategoryTermMutationResolver();
    }
}
