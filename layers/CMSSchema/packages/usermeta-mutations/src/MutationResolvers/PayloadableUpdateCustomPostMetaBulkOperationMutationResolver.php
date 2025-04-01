<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateUserMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateUserMetaMutationResolver $payloadableUpdateUserMetaMutationResolver = null;

    final protected function getPayloadableUpdateUserMetaMutationResolver(): PayloadableUpdateUserMetaMutationResolver
    {
        if ($this->payloadableUpdateUserMetaMutationResolver === null) {
            /** @var PayloadableUpdateUserMetaMutationResolver */
            $payloadableUpdateUserMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateUserMetaMutationResolver::class);
            $this->payloadableUpdateUserMetaMutationResolver = $payloadableUpdateUserMetaMutationResolver;
        }
        return $this->payloadableUpdateUserMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateUserMetaMutationResolver();
    }
}
