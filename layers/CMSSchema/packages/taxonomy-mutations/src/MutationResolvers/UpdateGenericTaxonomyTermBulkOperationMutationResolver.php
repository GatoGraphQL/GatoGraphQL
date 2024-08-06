<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateGenericTaxonomyTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateGenericTaxonomyTermMutationResolver $updateGenericTaxonomyTermMutationResolver = null;

    final public function setUpdateGenericTaxonomyTermMutationResolver(UpdateGenericTaxonomyTermMutationResolver $updateGenericTaxonomyTermMutationResolver): void
    {
        $this->updateGenericTaxonomyTermMutationResolver = $updateGenericTaxonomyTermMutationResolver;
    }
    final protected function getUpdateGenericTaxonomyTermMutationResolver(): UpdateGenericTaxonomyTermMutationResolver
    {
        if ($this->updateGenericTaxonomyTermMutationResolver === null) {
            /** @var UpdateGenericTaxonomyTermMutationResolver */
            $updateGenericTaxonomyTermMutationResolver = $this->instanceManager->getInstance(UpdateGenericTaxonomyTermMutationResolver::class);
            $this->updateGenericTaxonomyTermMutationResolver = $updateGenericTaxonomyTermMutationResolver;
        }
        return $this->updateGenericTaxonomyTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateGenericTaxonomyTermMutationResolver();
    }
}
