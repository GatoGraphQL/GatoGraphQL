<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateMenuBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateMenuMutationResolver $payloadableUpdateMenuMutationResolver = null;

    final protected function getPayloadableUpdateMenuMutationResolver(): PayloadableUpdateMenuMutationResolver
    {
        if ($this->payloadableUpdateMenuMutationResolver === null) {
            /** @var PayloadableUpdateMenuMutationResolver */
            $payloadableUpdateMenuMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateMenuMutationResolver::class);
            $this->payloadableUpdateMenuMutationResolver = $payloadableUpdateMenuMutationResolver;
        }
        return $this->payloadableUpdateMenuMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateMenuMutationResolver();
    }
}
