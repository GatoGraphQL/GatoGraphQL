<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeleteCommentMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeleteCommentMetaMutationResolver $deleteCommentMetaMutationResolver = null;

    final protected function getDeleteCommentMetaMutationResolver(): DeleteCommentMetaMutationResolver
    {
        if ($this->deleteCommentMetaMutationResolver === null) {
            /** @var DeleteCommentMetaMutationResolver */
            $deleteCommentMetaMutationResolver = $this->instanceManager->getInstance(DeleteCommentMetaMutationResolver::class);
            $this->deleteCommentMetaMutationResolver = $deleteCommentMetaMutationResolver;
        }
        return $this->deleteCommentMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeleteCommentMetaMutationResolver();
    }
}
