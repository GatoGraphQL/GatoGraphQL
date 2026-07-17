<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\MutationResolvers;

use PoPSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeletePageBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeletePageMutationResolver $payloadableDeletePageMutationResolver = null;

    final protected function getPayloadableDeletePageMutationResolver(): PayloadableDeletePageMutationResolver
    {
        if ($this->payloadableDeletePageMutationResolver === null) {
            /** @var PayloadableDeletePageMutationResolver */
            $payloadableDeletePageMutationResolver = $this->instanceManager->getInstance(PayloadableDeletePageMutationResolver::class);
            $this->payloadableDeletePageMutationResolver = $payloadableDeletePageMutationResolver;
        }
        return $this->payloadableDeletePageMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeletePageMutationResolver();
    }
}
