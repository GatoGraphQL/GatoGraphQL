<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class CreatePostTagTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?CreatePostTagTermMutationResolver $createPostTagTermMutationResolver = null;

    final protected function getCreatePostTagTermMutationResolver(): CreatePostTagTermMutationResolver
    {
        if ($this->createPostTagTermMutationResolver === null) {
            /** @var CreatePostTagTermMutationResolver */
            $createPostTagTermMutationResolver = $this->instanceManager->getInstance(CreatePostTagTermMutationResolver::class);
            $this->createPostTagTermMutationResolver = $createPostTagTermMutationResolver;
        }
        return $this->createPostTagTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getCreatePostTagTermMutationResolver();
    }
}
