<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateCommentMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateCommentMetaMutationResolver $updateCommentMetaMutationResolver = null;

    final protected function getUpdateCommentMetaMutationResolver(): UpdateCommentMetaMutationResolver
    {
        if ($this->updateCommentMetaMutationResolver === null) {
            /** @var UpdateCommentMetaMutationResolver */
            $updateCommentMetaMutationResolver = $this->instanceManager->getInstance(UpdateCommentMetaMutationResolver::class);
            $this->updateCommentMetaMutationResolver = $updateCommentMetaMutationResolver;
        }
        return $this->updateCommentMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateCommentMetaMutationResolver();
    }
}
