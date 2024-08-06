<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeleteGenericTaxonomyTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeleteGenericTaxonomyTermMutationResolver $payloadableDeleteGenericTaxonomyTermMutationResolver = null;

    final public function setPayloadableDeleteGenericTaxonomyTermMutationResolver(PayloadableDeleteGenericTaxonomyTermMutationResolver $payloadableDeleteGenericTaxonomyTermMutationResolver): void
    {
        $this->payloadableDeleteGenericTaxonomyTermMutationResolver = $payloadableDeleteGenericTaxonomyTermMutationResolver;
    }
    final protected function getPayloadableDeleteGenericTaxonomyTermMutationResolver(): PayloadableDeleteGenericTaxonomyTermMutationResolver
    {
        if ($this->payloadableDeleteGenericTaxonomyTermMutationResolver === null) {
            /** @var PayloadableDeleteGenericTaxonomyTermMutationResolver */
            $payloadableDeleteGenericTaxonomyTermMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericTaxonomyTermMutationResolver::class);
            $this->payloadableDeleteGenericTaxonomyTermMutationResolver = $payloadableDeleteGenericTaxonomyTermMutationResolver;
        }
        return $this->payloadableDeleteGenericTaxonomyTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeleteGenericTaxonomyTermMutationResolver();
    }
}
