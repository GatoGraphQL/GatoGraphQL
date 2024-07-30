<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class CreateGenericTaxonomyTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?CreateGenericTaxonomyTermMutationResolver $createGenericTaxonomyTermMutationResolver = null;

    final public function setCreateGenericTaxonomyTermMutationResolver(CreateGenericTaxonomyTermMutationResolver $createGenericTaxonomyTermMutationResolver): void
    {
        $this->createGenericTaxonomyTermMutationResolver = $createGenericTaxonomyTermMutationResolver;
    }
    final protected function getCreateGenericTaxonomyTermMutationResolver(): CreateGenericTaxonomyTermMutationResolver
    {
        if ($this->createGenericTaxonomyTermMutationResolver === null) {
            /** @var CreateGenericTaxonomyTermMutationResolver */
            $createGenericTaxonomyTermMutationResolver = $this->instanceManager->getInstance(CreateGenericTaxonomyTermMutationResolver::class);
            $this->createGenericTaxonomyTermMutationResolver = $createGenericTaxonomyTermMutationResolver;
        }
        return $this->createGenericTaxonomyTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateGenericTaxonomyTermMutationResolver();
    }
}
