<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class SetCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?SetCategoryTermMetaMutationResolver $setCategoryTermMetaMutationResolver = null;

    final protected function getSetCategoryTermMetaMutationResolver(): SetCategoryTermMetaMutationResolver
    {
        if ($this->setCategoryTermMetaMutationResolver === null) {
            /** @var SetCategoryTermMetaMutationResolver */
            $setCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(SetCategoryTermMetaMutationResolver::class);
            $this->setCategoryTermMetaMutationResolver = $setCategoryTermMetaMutationResolver;
        }
        return $this->setCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetCategoryTermMetaMutationResolver();
    }
}
