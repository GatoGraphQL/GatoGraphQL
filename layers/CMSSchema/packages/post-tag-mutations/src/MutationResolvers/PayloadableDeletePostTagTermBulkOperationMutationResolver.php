<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeletePostTagTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeletePostTagTermMutationResolver $payloadableDeletePostTagTermMutationResolver = null;

    final public function setPayloadableDeletePostTagTermMutationResolver(PayloadableDeletePostTagTermMutationResolver $payloadableDeletePostTagTermMutationResolver): void
    {
        $this->payloadableDeletePostTagTermMutationResolver = $payloadableDeletePostTagTermMutationResolver;
    }
    final protected function getPayloadableDeletePostTagTermMutationResolver(): PayloadableDeletePostTagTermMutationResolver
    {
        if ($this->payloadableDeletePostTagTermMutationResolver === null) {
            /** @var PayloadableDeletePostTagTermMutationResolver */
            $payloadableDeletePostTagTermMutationResolver = $this->instanceManager->getInstance(PayloadableDeletePostTagTermMutationResolver::class);
            $this->payloadableDeletePostTagTermMutationResolver = $payloadableDeletePostTagTermMutationResolver;
        }
        return $this->payloadableDeletePostTagTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeletePostTagTermMutationResolver();
    }
}
