<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableAddCommentMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableAddCommentMetaMutationResolver $payloadableAddCommentMetaMutationResolver = null;

    final protected function getPayloadableAddCommentMetaMutationResolver(): PayloadableAddCommentMetaMutationResolver
    {
        if ($this->payloadableAddCommentMetaMutationResolver === null) {
            /** @var PayloadableAddCommentMetaMutationResolver */
            $payloadableAddCommentMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddCommentMetaMutationResolver::class);
            $this->payloadableAddCommentMetaMutationResolver = $payloadableAddCommentMetaMutationResolver;
        }
        return $this->payloadableAddCommentMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableAddCommentMetaMutationResolver();
    }
}
