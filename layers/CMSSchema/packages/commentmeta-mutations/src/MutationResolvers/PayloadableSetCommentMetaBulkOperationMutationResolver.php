<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableSetCommentMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableSetCommentMetaMutationResolver $payloadableSetCommentMetaMutationResolver = null;

    final protected function getPayloadableSetCommentMetaMutationResolver(): PayloadableSetCommentMetaMutationResolver
    {
        if ($this->payloadableSetCommentMetaMutationResolver === null) {
            /** @var PayloadableSetCommentMetaMutationResolver */
            $payloadableSetCommentMetaMutationResolver = $this->instanceManager->getInstance(PayloadableSetCommentMetaMutationResolver::class);
            $this->payloadableSetCommentMetaMutationResolver = $payloadableSetCommentMetaMutationResolver;
        }
        return $this->payloadableSetCommentMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetCommentMetaMutationResolver();
    }
}
