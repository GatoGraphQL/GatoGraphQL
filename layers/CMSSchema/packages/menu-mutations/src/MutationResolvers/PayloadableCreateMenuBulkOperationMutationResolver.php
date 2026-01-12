<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableCreateMenuBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableCreateMenuMutationResolver $payloadableCreateMenuMutationResolver = null;

    final protected function getPayloadableCreateMenuMutationResolver(): PayloadableCreateMenuMutationResolver
    {
        if ($this->payloadableCreateMenuMutationResolver === null) {
            /** @var PayloadableCreateMenuMutationResolver */
            $payloadableCreateMenuMutationResolver = $this->instanceManager->getInstance(PayloadableCreateMenuMutationResolver::class);
            $this->payloadableCreateMenuMutationResolver = $payloadableCreateMenuMutationResolver;
        }
        return $this->payloadableCreateMenuMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableCreateMenuMutationResolver();
    }
}
