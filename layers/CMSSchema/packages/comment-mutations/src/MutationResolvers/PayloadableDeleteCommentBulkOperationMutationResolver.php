<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeleteCommentBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeleteCommentMutationResolver $payloadableDeleteCommentMutationResolver = null;

    final protected function getPayloadableDeleteCommentMutationResolver(): PayloadableDeleteCommentMutationResolver
    {
        if ($this->payloadableDeleteCommentMutationResolver === null) {
            /** @var PayloadableDeleteCommentMutationResolver */
            $payloadableDeleteCommentMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCommentMutationResolver::class);
            $this->payloadableDeleteCommentMutationResolver = $payloadableDeleteCommentMutationResolver;
        }
        return $this->payloadableDeleteCommentMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeleteCommentMutationResolver();
    }
}
