<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class SetCommentMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?SetCommentMetaMutationResolver $setCommentMetaMutationResolver = null;

    final protected function getSetCommentMetaMutationResolver(): SetCommentMetaMutationResolver
    {
        if ($this->setCommentMetaMutationResolver === null) {
            /** @var SetCommentMetaMutationResolver */
            $setCommentMetaMutationResolver = $this->instanceManager->getInstance(SetCommentMetaMutationResolver::class);
            $this->setCommentMetaMutationResolver = $setCommentMetaMutationResolver;
        }
        return $this->setCommentMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetCommentMetaMutationResolver();
    }
}
