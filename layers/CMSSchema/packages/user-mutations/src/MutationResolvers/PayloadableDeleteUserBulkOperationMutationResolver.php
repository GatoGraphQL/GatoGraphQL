<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeleteUserBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeleteUserMutationResolver $payloadableDeleteUserMutationResolver = null;

    final protected function getPayloadableDeleteUserMutationResolver(): PayloadableDeleteUserMutationResolver
    {
        if ($this->payloadableDeleteUserMutationResolver === null) {
            /** @var PayloadableDeleteUserMutationResolver */
            $payloadableDeleteUserMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteUserMutationResolver::class);
            $this->payloadableDeleteUserMutationResolver = $payloadableDeleteUserMutationResolver;
        }
        return $this->payloadableDeleteUserMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeleteUserMutationResolver();
    }
}
