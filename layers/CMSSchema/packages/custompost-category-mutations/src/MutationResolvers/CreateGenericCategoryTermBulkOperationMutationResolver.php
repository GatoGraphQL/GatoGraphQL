<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class CreateGenericCategoryTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?CreateGenericCategoryTermMutationResolver $createGenericCategoryTermMutationResolver = null;

    final public function setCreateGenericCategoryTermMutationResolver(CreateGenericCategoryTermMutationResolver $createGenericCategoryTermMutationResolver): void
    {
        $this->createGenericCategoryTermMutationResolver = $createGenericCategoryTermMutationResolver;
    }
    final protected function getCreateGenericCategoryTermMutationResolver(): CreateGenericCategoryTermMutationResolver
    {
        if ($this->createGenericCategoryTermMutationResolver === null) {
            /** @var CreateGenericCategoryTermMutationResolver */
            $createGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(CreateGenericCategoryTermMutationResolver::class);
            $this->createGenericCategoryTermMutationResolver = $createGenericCategoryTermMutationResolver;
        }
        return $this->createGenericCategoryTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateGenericCategoryTermMutationResolver();
    }
}
