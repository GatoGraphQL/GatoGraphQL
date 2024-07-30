<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class CreateGenericCategoryBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?CreateGenericCategoryMutationResolver $createGenericCategoryMutationResolver = null;

    final public function setCreateGenericCategoryMutationResolver(CreateGenericCategoryMutationResolver $createGenericCategoryMutationResolver): void
    {
        $this->createGenericCategoryMutationResolver = $createGenericCategoryMutationResolver;
    }
    final protected function getCreateGenericCategoryMutationResolver(): CreateGenericCategoryMutationResolver
    {
        if ($this->createGenericCategoryMutationResolver === null) {
            /** @var CreateGenericCategoryMutationResolver */
            $createGenericCategoryMutationResolver = $this->instanceManager->getInstance(CreateGenericCategoryMutationResolver::class);
            $this->createGenericCategoryMutationResolver = $createGenericCategoryMutationResolver;
        }
        return $this->createGenericCategoryMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateGenericCategoryMutationResolver();
    }
}
