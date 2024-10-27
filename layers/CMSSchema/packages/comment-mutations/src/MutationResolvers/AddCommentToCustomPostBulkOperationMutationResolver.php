<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class AddCommentToCustomPostBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver = null;

    final protected function getAddCommentToCustomPostMutationResolver(): AddCommentToCustomPostMutationResolver
    {
        if ($this->addCommentToCustomPostMutationResolver === null) {
            /** @var AddCommentToCustomPostMutationResolver */
            $addCommentToCustomPostMutationResolver = $this->instanceManager->getInstance(AddCommentToCustomPostMutationResolver::class);
            $this->addCommentToCustomPostMutationResolver = $addCommentToCustomPostMutationResolver;
        }
        return $this->addCommentToCustomPostMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getAddCommentToCustomPostMutationResolver();
    }
}
