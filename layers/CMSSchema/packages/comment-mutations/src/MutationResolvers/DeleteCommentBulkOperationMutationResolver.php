<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeleteCommentBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeleteCommentMutationResolver $deleteCommentMutationResolver = null;

    final protected function getDeleteCommentMutationResolver(): DeleteCommentMutationResolver
    {
        if ($this->deleteCommentMutationResolver === null) {
            /** @var DeleteCommentMutationResolver */
            $deleteCommentMutationResolver = $this->instanceManager->getInstance(DeleteCommentMutationResolver::class);
            $this->deleteCommentMutationResolver = $deleteCommentMutationResolver;
        }
        return $this->deleteCommentMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeleteCommentMutationResolver();
    }
}
