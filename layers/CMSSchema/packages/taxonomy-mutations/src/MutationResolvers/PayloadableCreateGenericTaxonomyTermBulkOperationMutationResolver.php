<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableCreateGenericTaxonomyTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableCreateGenericTaxonomyTermMutationResolver $payloadableCreateGenericTaxonomyTermMutationResolver = null;

    final public function setPayloadableCreateGenericTaxonomyTermMutationResolver(PayloadableCreateGenericTaxonomyTermMutationResolver $payloadableCreateGenericTaxonomyTermMutationResolver): void
    {
        $this->payloadableCreateGenericTaxonomyTermMutationResolver = $payloadableCreateGenericTaxonomyTermMutationResolver;
    }
    final protected function getPayloadableCreateGenericTaxonomyTermMutationResolver(): PayloadableCreateGenericTaxonomyTermMutationResolver
    {
        if ($this->payloadableCreateGenericTaxonomyTermMutationResolver === null) {
            /** @var PayloadableCreateGenericTaxonomyTermMutationResolver */
            $payloadableCreateGenericTaxonomyTermMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericTaxonomyTermMutationResolver::class);
            $this->payloadableCreateGenericTaxonomyTermMutationResolver = $payloadableCreateGenericTaxonomyTermMutationResolver;
        }
        return $this->payloadableCreateGenericTaxonomyTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableCreateGenericTaxonomyTermMutationResolver();
    }
}
