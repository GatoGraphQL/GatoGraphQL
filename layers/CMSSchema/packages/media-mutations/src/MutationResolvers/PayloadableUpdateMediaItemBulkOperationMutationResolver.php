<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateMediaItemBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateMediaItemMutationResolver $payloadableUpdateMediaItemMutationResolver = null;

    final public function setPayloadableUpdateMediaItemMutationResolver(PayloadableUpdateMediaItemMutationResolver $payloadableUpdateMediaItemMutationResolver): void
    {
        $this->payloadableUpdateMediaItemMutationResolver = $payloadableUpdateMediaItemMutationResolver;
    }
    final protected function getPayloadableUpdateMediaItemMutationResolver(): PayloadableUpdateMediaItemMutationResolver
    {
        if ($this->payloadableUpdateMediaItemMutationResolver === null) {
            /** @var PayloadableUpdateMediaItemMutationResolver */
            $payloadableUpdateMediaItemMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateMediaItemMutationResolver::class);
            $this->payloadableUpdateMediaItemMutationResolver = $payloadableUpdateMediaItemMutationResolver;
        }
        return $this->payloadableUpdateMediaItemMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateMediaItemMutationResolver();
    }
}
