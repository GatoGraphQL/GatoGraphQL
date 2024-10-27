<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeleteGenericCategoryTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeleteGenericCategoryTermMutationResolver $deleteGenericCategoryTermMutationResolver = null;

    final protected function getDeleteGenericCategoryTermMutationResolver(): DeleteGenericCategoryTermMutationResolver
    {
        if ($this->deleteGenericCategoryTermMutationResolver === null) {
            /** @var DeleteGenericCategoryTermMutationResolver */
            $deleteGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(DeleteGenericCategoryTermMutationResolver::class);
            $this->deleteGenericCategoryTermMutationResolver = $deleteGenericCategoryTermMutationResolver;
        }
        return $this->deleteGenericCategoryTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeleteGenericCategoryTermMutationResolver();
    }
}
