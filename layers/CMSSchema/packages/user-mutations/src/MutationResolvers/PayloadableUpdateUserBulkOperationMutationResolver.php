<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateUserBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateUserMutationResolver $payloadableUpdateUserMutationResolver = null;

    final protected function getPayloadableUpdateUserMutationResolver(): PayloadableUpdateUserMutationResolver
    {
        if ($this->payloadableUpdateUserMutationResolver === null) {
            /** @var PayloadableUpdateUserMutationResolver */
            $payloadableUpdateUserMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateUserMutationResolver::class);
            $this->payloadableUpdateUserMutationResolver = $payloadableUpdateUserMutationResolver;
        }
        return $this->payloadableUpdateUserMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateUserMutationResolver();
    }
}
