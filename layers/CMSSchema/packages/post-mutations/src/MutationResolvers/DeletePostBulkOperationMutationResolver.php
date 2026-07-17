<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\MutationResolvers;

use PoPSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeletePostBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeletePostMutationResolver $deletePostMutationResolver = null;

    final protected function getDeletePostMutationResolver(): DeletePostMutationResolver
    {
        if ($this->deletePostMutationResolver === null) {
            /** @var DeletePostMutationResolver */
            $deletePostMutationResolver = $this->instanceManager->getInstance(DeletePostMutationResolver::class);
            $this->deletePostMutationResolver = $deletePostMutationResolver;
        }
        return $this->deletePostMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeletePostMutationResolver();
    }
}
