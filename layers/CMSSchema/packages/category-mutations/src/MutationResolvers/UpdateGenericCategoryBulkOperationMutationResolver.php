<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateGenericCategoryBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateGenericCategoryMutationResolver $updateGenericCategoryMutationResolver = null;

    final public function setUpdateGenericCategoryMutationResolver(UpdateGenericCategoryMutationResolver $updateGenericCategoryMutationResolver): void
    {
        $this->updateGenericCategoryMutationResolver = $updateGenericCategoryMutationResolver;
    }
    final protected function getUpdateGenericCategoryMutationResolver(): UpdateGenericCategoryMutationResolver
    {
        if ($this->updateGenericCategoryMutationResolver === null) {
            /** @var UpdateGenericCategoryMutationResolver */
            $updateGenericCategoryMutationResolver = $this->instanceManager->getInstance(UpdateGenericCategoryMutationResolver::class);
            $this->updateGenericCategoryMutationResolver = $updateGenericCategoryMutationResolver;
        }
        return $this->updateGenericCategoryMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateGenericCategoryMutationResolver();
    }
}
