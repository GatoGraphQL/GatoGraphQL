<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeleteGenericTaxonomyTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeleteGenericTaxonomyTermMutationResolver $deleteGenericTaxonomyTermMutationResolver = null;

    final public function setDeleteGenericTaxonomyTermMutationResolver(DeleteGenericTaxonomyTermMutationResolver $deleteGenericTaxonomyTermMutationResolver): void
    {
        $this->deleteGenericTaxonomyTermMutationResolver = $deleteGenericTaxonomyTermMutationResolver;
    }
    final protected function getDeleteGenericTaxonomyTermMutationResolver(): DeleteGenericTaxonomyTermMutationResolver
    {
        if ($this->deleteGenericTaxonomyTermMutationResolver === null) {
            /** @var DeleteGenericTaxonomyTermMutationResolver */
            $deleteGenericTaxonomyTermMutationResolver = $this->instanceManager->getInstance(DeleteGenericTaxonomyTermMutationResolver::class);
            $this->deleteGenericTaxonomyTermMutationResolver = $deleteGenericTaxonomyTermMutationResolver;
        }
        return $this->deleteGenericTaxonomyTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeleteGenericTaxonomyTermMutationResolver();
    }
}
