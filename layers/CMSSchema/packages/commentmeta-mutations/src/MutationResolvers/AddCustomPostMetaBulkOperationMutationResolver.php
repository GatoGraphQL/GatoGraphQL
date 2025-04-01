<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class AddCommentMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?AddCommentMetaMutationResolver $addCommentMetaMutationResolver = null;

    final protected function getAddCommentMetaMutationResolver(): AddCommentMetaMutationResolver
    {
        if ($this->addCommentMetaMutationResolver === null) {
            /** @var AddCommentMetaMutationResolver */
            $addCommentMetaMutationResolver = $this->instanceManager->getInstance(AddCommentMetaMutationResolver::class);
            $this->addCommentMetaMutationResolver = $addCommentMetaMutationResolver;
        }
        return $this->addCommentMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getAddCommentMetaMutationResolver();
    }
}
