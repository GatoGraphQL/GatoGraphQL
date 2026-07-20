<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeleteMediaItemBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeleteMediaItemMutationResolver $deleteMediaItemMutationResolver = null;

    final protected function getDeleteMediaItemMutationResolver(): DeleteMediaItemMutationResolver
    {
        if ($this->deleteMediaItemMutationResolver === null) {
            /** @var DeleteMediaItemMutationResolver */
            $deleteMediaItemMutationResolver = $this->instanceManager->getInstance(DeleteMediaItemMutationResolver::class);
            $this->deleteMediaItemMutationResolver = $deleteMediaItemMutationResolver;
        }
        return $this->deleteMediaItemMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeleteMediaItemMutationResolver();
    }
}
