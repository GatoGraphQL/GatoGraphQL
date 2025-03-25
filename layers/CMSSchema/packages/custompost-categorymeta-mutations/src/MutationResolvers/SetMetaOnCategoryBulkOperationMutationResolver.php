<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class SetMetaOnCategoryBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?SetMetaOnCategoryMutationResolver $setMetaOnCategoryMutationResolver = null;

    final protected function getSetMetaOnCategoryMutationResolver(): SetMetaOnCategoryMutationResolver
    {
        if ($this->setMetaOnCategoryMutationResolver === null) {
            /** @var SetMetaOnCategoryMutationResolver */
            $setMetaOnCategoryMutationResolver = $this->instanceManager->getInstance(SetMetaOnCategoryMutationResolver::class);
            $this->setMetaOnCategoryMutationResolver = $setMetaOnCategoryMutationResolver;
        }
        return $this->setMetaOnCategoryMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetMetaOnCategoryMutationResolver();
    }
}
