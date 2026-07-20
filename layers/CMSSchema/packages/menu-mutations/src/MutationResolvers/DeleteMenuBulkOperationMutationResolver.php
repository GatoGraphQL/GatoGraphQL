<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeleteMenuBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeleteMenuMutationResolver $deleteMenuMutationResolver = null;

    final protected function getDeleteMenuMutationResolver(): DeleteMenuMutationResolver
    {
        if ($this->deleteMenuMutationResolver === null) {
            /** @var DeleteMenuMutationResolver */
            $deleteMenuMutationResolver = $this->instanceManager->getInstance(DeleteMenuMutationResolver::class);
            $this->deleteMenuMutationResolver = $deleteMenuMutationResolver;
        }
        return $this->deleteMenuMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeleteMenuMutationResolver();
    }
}
