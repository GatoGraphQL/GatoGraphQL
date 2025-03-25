<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class CreateGenericCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?CreateGenericCategoryTermMetaMutationResolver $createGenericCategoryTermMetaMutationResolver = null;

    final protected function getCreateGenericCategoryTermMetaMutationResolver(): CreateGenericCategoryTermMetaMutationResolver
    {
        if ($this->createGenericCategoryTermMetaMutationResolver === null) {
            /** @var CreateGenericCategoryTermMetaMutationResolver */
            $createGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(CreateGenericCategoryTermMetaMutationResolver::class);
            $this->createGenericCategoryTermMetaMutationResolver = $createGenericCategoryTermMetaMutationResolver;
        }
        return $this->createGenericCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateGenericCategoryTermMetaMutationResolver();
    }
}
