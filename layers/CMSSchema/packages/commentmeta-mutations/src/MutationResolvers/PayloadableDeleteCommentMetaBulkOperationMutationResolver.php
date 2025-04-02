<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeleteCommentMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeleteCommentMetaMutationResolver $payloadableDeleteCommentMetaMutationResolver = null;

    final protected function getPayloadableDeleteCommentMetaMutationResolver(): PayloadableDeleteCommentMetaMutationResolver
    {
        if ($this->payloadableDeleteCommentMetaMutationResolver === null) {
            /** @var PayloadableDeleteCommentMetaMutationResolver */
            $payloadableDeleteCommentMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCommentMetaMutationResolver::class);
            $this->payloadableDeleteCommentMetaMutationResolver = $payloadableDeleteCommentMetaMutationResolver;
        }
        return $this->payloadableDeleteCommentMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeleteCommentMetaMutationResolver();
    }
}
