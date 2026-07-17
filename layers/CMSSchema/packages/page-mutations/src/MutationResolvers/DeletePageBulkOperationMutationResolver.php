<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\MutationResolvers;

use PoPSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeletePageBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeletePageMutationResolver $deletePageMutationResolver = null;

    final protected function getDeletePageMutationResolver(): DeletePageMutationResolver
    {
        if ($this->deletePageMutationResolver === null) {
            /** @var DeletePageMutationResolver */
            $deletePageMutationResolver = $this->instanceManager->getInstance(DeletePageMutationResolver::class);
            $this->deletePageMutationResolver = $deletePageMutationResolver;
        }
        return $this->deletePageMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeletePageMutationResolver();
    }
}
