<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class SetGenericCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?SetGenericCategoryTermMetaMutationResolver $setGenericCategoryTermMetaMutationResolver = null;

    final protected function getSetGenericCategoryTermMetaMutationResolver(): SetGenericCategoryTermMetaMutationResolver
    {
        if ($this->setGenericCategoryTermMetaMutationResolver === null) {
            /** @var SetGenericCategoryTermMetaMutationResolver */
            $setGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(SetGenericCategoryTermMetaMutationResolver::class);
            $this->setGenericCategoryTermMetaMutationResolver = $setGenericCategoryTermMetaMutationResolver;
        }
        return $this->setGenericCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetGenericCategoryTermMetaMutationResolver();
    }
}
