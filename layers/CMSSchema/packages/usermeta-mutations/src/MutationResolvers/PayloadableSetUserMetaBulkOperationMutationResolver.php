<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableSetUserMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableSetUserMetaMutationResolver $payloadableSetUserMetaMutationResolver = null;

    final protected function getPayloadableSetUserMetaMutationResolver(): PayloadableSetUserMetaMutationResolver
    {
        if ($this->payloadableSetUserMetaMutationResolver === null) {
            /** @var PayloadableSetUserMetaMutationResolver */
            $payloadableSetUserMetaMutationResolver = $this->instanceManager->getInstance(PayloadableSetUserMetaMutationResolver::class);
            $this->payloadableSetUserMetaMutationResolver = $payloadableSetUserMetaMutationResolver;
        }
        return $this->payloadableSetUserMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetUserMetaMutationResolver();
    }
}
