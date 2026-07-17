<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeleteGenericCustomPostBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeleteGenericCustomPostMutationResolver $payloadableDeleteGenericCustomPostMutationResolver = null;

    final protected function getPayloadableDeleteGenericCustomPostMutationResolver(): PayloadableDeleteGenericCustomPostMutationResolver
    {
        if ($this->payloadableDeleteGenericCustomPostMutationResolver === null) {
            /** @var PayloadableDeleteGenericCustomPostMutationResolver */
            $payloadableDeleteGenericCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericCustomPostMutationResolver::class);
            $this->payloadableDeleteGenericCustomPostMutationResolver = $payloadableDeleteGenericCustomPostMutationResolver;
        }
        return $this->payloadableDeleteGenericCustomPostMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeleteGenericCustomPostMutationResolver();
    }
}
