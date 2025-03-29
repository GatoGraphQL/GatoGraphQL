<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateCategoryTermMetaMutationResolver $updateCategoryTermMetaMutationResolver = null;

    final protected function getUpdateCategoryTermMetaMutationResolver(): UpdateCategoryTermMetaMutationResolver
    {
        if ($this->updateCategoryTermMetaMutationResolver === null) {
            /** @var UpdateCategoryTermMetaMutationResolver */
            $updateCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(UpdateCategoryTermMetaMutationResolver::class);
            $this->updateCategoryTermMetaMutationResolver = $updateCategoryTermMetaMutationResolver;
        }
        return $this->updateCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateCategoryTermMetaMutationResolver();
    }
}
