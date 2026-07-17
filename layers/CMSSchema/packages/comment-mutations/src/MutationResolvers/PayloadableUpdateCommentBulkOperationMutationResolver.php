<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateCommentBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateCommentMutationResolver $payloadableUpdateCommentMutationResolver = null;

    final protected function getPayloadableUpdateCommentMutationResolver(): PayloadableUpdateCommentMutationResolver
    {
        if ($this->payloadableUpdateCommentMutationResolver === null) {
            /** @var PayloadableUpdateCommentMutationResolver */
            $payloadableUpdateCommentMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCommentMutationResolver::class);
            $this->payloadableUpdateCommentMutationResolver = $payloadableUpdateCommentMutationResolver;
        }
        return $this->payloadableUpdateCommentMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateCommentMutationResolver();
    }
}
