<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeleteMediaItemBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeleteMediaItemMutationResolver $payloadableDeleteMediaItemMutationResolver = null;

    final protected function getPayloadableDeleteMediaItemMutationResolver(): PayloadableDeleteMediaItemMutationResolver
    {
        if ($this->payloadableDeleteMediaItemMutationResolver === null) {
            /** @var PayloadableDeleteMediaItemMutationResolver */
            $payloadableDeleteMediaItemMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteMediaItemMutationResolver::class);
            $this->payloadableDeleteMediaItemMutationResolver = $payloadableDeleteMediaItemMutationResolver;
        }
        return $this->payloadableDeleteMediaItemMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeleteMediaItemMutationResolver();
    }
}
