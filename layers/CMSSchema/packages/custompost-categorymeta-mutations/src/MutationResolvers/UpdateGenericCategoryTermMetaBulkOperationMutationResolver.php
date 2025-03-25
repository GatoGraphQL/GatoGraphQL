<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateGenericCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateGenericCategoryTermMetaMutationResolver $updateGenericCategoryTermMetaMutationResolver = null;

    final protected function getUpdateGenericCategoryTermMetaMutationResolver(): UpdateGenericCategoryTermMetaMutationResolver
    {
        if ($this->updateGenericCategoryTermMetaMutationResolver === null) {
            /** @var UpdateGenericCategoryTermMetaMutationResolver */
            $updateGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(UpdateGenericCategoryTermMetaMutationResolver::class);
            $this->updateGenericCategoryTermMetaMutationResolver = $updateGenericCategoryTermMetaMutationResolver;
        }
        return $this->updateGenericCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateGenericCategoryTermMetaMutationResolver();
    }
}
