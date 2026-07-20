<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeleteMenuBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeleteMenuMutationResolver $payloadableDeleteMenuMutationResolver = null;

    final protected function getPayloadableDeleteMenuMutationResolver(): PayloadableDeleteMenuMutationResolver
    {
        if ($this->payloadableDeleteMenuMutationResolver === null) {
            /** @var PayloadableDeleteMenuMutationResolver */
            $payloadableDeleteMenuMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteMenuMutationResolver::class);
            $this->payloadableDeleteMenuMutationResolver = $payloadableDeleteMenuMutationResolver;
        }
        return $this->payloadableDeleteMenuMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeleteMenuMutationResolver();
    }
}
