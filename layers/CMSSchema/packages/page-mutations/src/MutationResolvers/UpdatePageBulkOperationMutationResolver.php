<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdatePageBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdatePageMutationResolver $updatePageMutationResolver = null;

    final protected function getUpdatePageMutationResolver(): UpdatePageMutationResolver
    {
        if ($this->updatePageMutationResolver === null) {
            /** @var UpdatePageMutationResolver */
            $updatePageMutationResolver = $this->instanceManager->getInstance(UpdatePageMutationResolver::class);
            $this->updatePageMutationResolver = $updatePageMutationResolver;
        }
        return $this->updatePageMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdatePageMutationResolver();
    }
}
