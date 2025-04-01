<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableAddUserMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableAddUserMetaMutationResolver $payloadableAddUserMetaMutationResolver = null;

    final protected function getPayloadableAddUserMetaMutationResolver(): PayloadableAddUserMetaMutationResolver
    {
        if ($this->payloadableAddUserMetaMutationResolver === null) {
            /** @var PayloadableAddUserMetaMutationResolver */
            $payloadableAddUserMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddUserMetaMutationResolver::class);
            $this->payloadableAddUserMetaMutationResolver = $payloadableAddUserMetaMutationResolver;
        }
        return $this->payloadableAddUserMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableAddUserMetaMutationResolver();
    }
}
