<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateGenericTagTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateGenericTagTermMutationResolver $payloadableUpdateGenericTagTermMutationResolver = null;

    final protected function getPayloadableUpdateGenericTagTermMutationResolver(): PayloadableUpdateGenericTagTermMutationResolver
    {
        if ($this->payloadableUpdateGenericTagTermMutationResolver === null) {
            /** @var PayloadableUpdateGenericTagTermMutationResolver */
            $payloadableUpdateGenericTagTermMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericTagTermMutationResolver::class);
            $this->payloadableUpdateGenericTagTermMutationResolver = $payloadableUpdateGenericTagTermMutationResolver;
        }
        return $this->payloadableUpdateGenericTagTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateGenericTagTermMutationResolver();
    }
}
