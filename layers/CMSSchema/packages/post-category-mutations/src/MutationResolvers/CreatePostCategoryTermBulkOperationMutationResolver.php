<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class CreatePostCategoryTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?CreatePostCategoryTermMutationResolver $createPostCategoryTermMutationResolver = null;

    final public function setCreatePostCategoryTermMutationResolver(CreatePostCategoryTermMutationResolver $createPostCategoryTermMutationResolver): void
    {
        $this->createPostCategoryTermMutationResolver = $createPostCategoryTermMutationResolver;
    }
    final protected function getCreatePostCategoryTermMutationResolver(): CreatePostCategoryTermMutationResolver
    {
        if ($this->createPostCategoryTermMutationResolver === null) {
            /** @var CreatePostCategoryTermMutationResolver */
            $createPostCategoryTermMutationResolver = $this->instanceManager->getInstance(CreatePostCategoryTermMutationResolver::class);
            $this->createPostCategoryTermMutationResolver = $createPostCategoryTermMutationResolver;
        }
        return $this->createPostCategoryTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getCreatePostCategoryTermMutationResolver();
    }
}
