<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class AddGenericCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?AddGenericCategoryTermMetaMutationResolver $addGenericCategoryTermMetaMutationResolver = null;

    final protected function getAddGenericCategoryTermMetaMutationResolver(): AddGenericCategoryTermMetaMutationResolver
    {
        if ($this->addGenericCategoryTermMetaMutationResolver === null) {
            /** @var AddGenericCategoryTermMetaMutationResolver */
            $addGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(AddGenericCategoryTermMetaMutationResolver::class);
            $this->addGenericCategoryTermMetaMutationResolver = $addGenericCategoryTermMetaMutationResolver;
        }
        return $this->addGenericCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getAddGenericCategoryTermMetaMutationResolver();
    }
}
