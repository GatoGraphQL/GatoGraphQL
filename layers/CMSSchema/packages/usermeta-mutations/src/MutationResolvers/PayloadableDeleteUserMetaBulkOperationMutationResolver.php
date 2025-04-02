<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeleteUserMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeleteUserMetaMutationResolver $payloadableDeleteUserMetaMutationResolver = null;

    final protected function getPayloadableDeleteUserMetaMutationResolver(): PayloadableDeleteUserMetaMutationResolver
    {
        if ($this->payloadableDeleteUserMetaMutationResolver === null) {
            /** @var PayloadableDeleteUserMetaMutationResolver */
            $payloadableDeleteUserMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteUserMetaMutationResolver::class);
            $this->payloadableDeleteUserMetaMutationResolver = $payloadableDeleteUserMetaMutationResolver;
        }
        return $this->payloadableDeleteUserMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeleteUserMetaMutationResolver();
    }
}
