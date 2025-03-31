<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateCustomPostMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateCustomPostMetaMutationResolver $payloadableUpdateCustomPostMetaMutationResolver = null;

    final protected function getPayloadableUpdateCustomPostMetaMutationResolver(): PayloadableUpdateCustomPostMetaMutationResolver
    {
        if ($this->payloadableUpdateCustomPostMetaMutationResolver === null) {
            /** @var PayloadableUpdateCustomPostMetaMutationResolver */
            $payloadableUpdateCustomPostMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCustomPostMetaMutationResolver::class);
            $this->payloadableUpdateCustomPostMetaMutationResolver = $payloadableUpdateCustomPostMetaMutationResolver;
        }
        return $this->payloadableUpdateCustomPostMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateCustomPostMetaMutationResolver();
    }
}
