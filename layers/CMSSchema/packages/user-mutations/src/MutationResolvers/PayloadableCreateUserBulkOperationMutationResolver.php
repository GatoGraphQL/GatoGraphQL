<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableCreateUserBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableCreateUserMutationResolver $payloadableCreateUserMutationResolver = null;

    final protected function getPayloadableCreateUserMutationResolver(): PayloadableCreateUserMutationResolver
    {
        if ($this->payloadableCreateUserMutationResolver === null) {
            /** @var PayloadableCreateUserMutationResolver */
            $payloadableCreateUserMutationResolver = $this->instanceManager->getInstance(PayloadableCreateUserMutationResolver::class);
            $this->payloadableCreateUserMutationResolver = $payloadableCreateUserMutationResolver;
        }
        return $this->payloadableCreateUserMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableCreateUserMutationResolver();
    }
}
