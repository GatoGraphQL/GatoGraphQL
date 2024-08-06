<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateGenericTaxonomyTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateGenericTaxonomyTermMutationResolver $payloadableUpdateGenericTaxonomyTermMutationResolver = null;

    final public function setPayloadableUpdateGenericTaxonomyTermMutationResolver(PayloadableUpdateGenericTaxonomyTermMutationResolver $payloadableUpdateGenericTaxonomyTermMutationResolver): void
    {
        $this->payloadableUpdateGenericTaxonomyTermMutationResolver = $payloadableUpdateGenericTaxonomyTermMutationResolver;
    }
    final protected function getPayloadableUpdateGenericTaxonomyTermMutationResolver(): PayloadableUpdateGenericTaxonomyTermMutationResolver
    {
        if ($this->payloadableUpdateGenericTaxonomyTermMutationResolver === null) {
            /** @var PayloadableUpdateGenericTaxonomyTermMutationResolver */
            $payloadableUpdateGenericTaxonomyTermMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericTaxonomyTermMutationResolver::class);
            $this->payloadableUpdateGenericTaxonomyTermMutationResolver = $payloadableUpdateGenericTaxonomyTermMutationResolver;
        }
        return $this->payloadableUpdateGenericTaxonomyTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateGenericTaxonomyTermMutationResolver();
    }
}
