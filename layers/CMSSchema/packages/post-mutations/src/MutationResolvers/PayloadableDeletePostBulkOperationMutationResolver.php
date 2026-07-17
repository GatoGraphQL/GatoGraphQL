<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeletePostBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeletePostMutationResolver $payloadableDeletePostMutationResolver = null;

    final protected function getPayloadableDeletePostMutationResolver(): PayloadableDeletePostMutationResolver
    {
        if ($this->payloadableDeletePostMutationResolver === null) {
            /** @var PayloadableDeletePostMutationResolver */
            $payloadableDeletePostMutationResolver = $this->instanceManager->getInstance(PayloadableDeletePostMutationResolver::class);
            $this->payloadableDeletePostMutationResolver = $payloadableDeletePostMutationResolver;
        }
        return $this->payloadableDeletePostMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeletePostMutationResolver();
    }
}
