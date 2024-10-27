<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeleteGenericTagTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeleteGenericTagTermMutationResolver $payloadableDeleteGenericTagTermMutationResolver = null;

    final protected function getPayloadableDeleteGenericTagTermMutationResolver(): PayloadableDeleteGenericTagTermMutationResolver
    {
        if ($this->payloadableDeleteGenericTagTermMutationResolver === null) {
            /** @var PayloadableDeleteGenericTagTermMutationResolver */
            $payloadableDeleteGenericTagTermMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericTagTermMutationResolver::class);
            $this->payloadableDeleteGenericTagTermMutationResolver = $payloadableDeleteGenericTagTermMutationResolver;
        }
        return $this->payloadableDeleteGenericTagTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeleteGenericTagTermMutationResolver();
    }
}
