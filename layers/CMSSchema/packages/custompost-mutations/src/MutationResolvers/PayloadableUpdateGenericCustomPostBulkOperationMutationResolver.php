<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateGenericCustomPostBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateGenericCustomPostMutationResolver $payloadableUpdateGenericCustomPostMutationResolver = null;

    final protected function getPayloadableUpdateGenericCustomPostMutationResolver(): PayloadableUpdateGenericCustomPostMutationResolver
    {
        if ($this->payloadableUpdateGenericCustomPostMutationResolver === null) {
            /** @var PayloadableUpdateGenericCustomPostMutationResolver */
            $payloadableUpdateGenericCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCustomPostMutationResolver::class);
            $this->payloadableUpdateGenericCustomPostMutationResolver = $payloadableUpdateGenericCustomPostMutationResolver;
        }
        return $this->payloadableUpdateGenericCustomPostMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateGenericCustomPostMutationResolver();
    }
}
