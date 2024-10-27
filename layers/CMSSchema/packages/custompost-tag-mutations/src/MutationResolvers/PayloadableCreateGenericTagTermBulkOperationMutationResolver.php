<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableCreateGenericTagTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableCreateGenericTagTermMutationResolver $payloadableCreateGenericTagTermMutationResolver = null;

    final protected function getPayloadableCreateGenericTagTermMutationResolver(): PayloadableCreateGenericTagTermMutationResolver
    {
        if ($this->payloadableCreateGenericTagTermMutationResolver === null) {
            /** @var PayloadableCreateGenericTagTermMutationResolver */
            $payloadableCreateGenericTagTermMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericTagTermMutationResolver::class);
            $this->payloadableCreateGenericTagTermMutationResolver = $payloadableCreateGenericTagTermMutationResolver;
        }
        return $this->payloadableCreateGenericTagTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableCreateGenericTagTermMutationResolver();
    }
}
