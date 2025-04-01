<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateCommentMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateCommentMetaMutationResolver $payloadableUpdateCommentMetaMutationResolver = null;

    final protected function getPayloadableUpdateCommentMetaMutationResolver(): PayloadableUpdateCommentMetaMutationResolver
    {
        if ($this->payloadableUpdateCommentMetaMutationResolver === null) {
            /** @var PayloadableUpdateCommentMetaMutationResolver */
            $payloadableUpdateCommentMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCommentMetaMutationResolver::class);
            $this->payloadableUpdateCommentMetaMutationResolver = $payloadableUpdateCommentMetaMutationResolver;
        }
        return $this->payloadableUpdateCommentMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateCommentMetaMutationResolver();
    }
}
