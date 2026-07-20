<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateCommentBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateCommentMutationResolver $updateCommentMutationResolver = null;

    final protected function getUpdateCommentMutationResolver(): UpdateCommentMutationResolver
    {
        if ($this->updateCommentMutationResolver === null) {
            /** @var UpdateCommentMutationResolver */
            $updateCommentMutationResolver = $this->instanceManager->getInstance(UpdateCommentMutationResolver::class);
            $this->updateCommentMutationResolver = $updateCommentMutationResolver;
        }
        return $this->updateCommentMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateCommentMutationResolver();
    }
}
