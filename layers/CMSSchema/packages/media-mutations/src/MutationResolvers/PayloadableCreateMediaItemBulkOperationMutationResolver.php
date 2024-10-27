<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableCreateMediaItemBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableCreateMediaItemMutationResolver $payloadableCreateMediaItemMutationResolver = null;

    final protected function getPayloadableCreateMediaItemMutationResolver(): PayloadableCreateMediaItemMutationResolver
    {
        if ($this->payloadableCreateMediaItemMutationResolver === null) {
            /** @var PayloadableCreateMediaItemMutationResolver */
            $payloadableCreateMediaItemMutationResolver = $this->instanceManager->getInstance(PayloadableCreateMediaItemMutationResolver::class);
            $this->payloadableCreateMediaItemMutationResolver = $payloadableCreateMediaItemMutationResolver;
        }
        return $this->payloadableCreateMediaItemMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableCreateMediaItemMutationResolver();
    }
}
