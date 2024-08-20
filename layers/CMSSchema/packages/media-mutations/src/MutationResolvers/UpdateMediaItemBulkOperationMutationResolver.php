<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateMediaItemBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateMediaItemMutationResolver $updateMediaItemMutationResolver = null;

    final public function setUpdateMediaItemMutationResolver(UpdateMediaItemMutationResolver $updateMediaItemMutationResolver): void
    {
        $this->updateMediaItemMutationResolver = $updateMediaItemMutationResolver;
    }
    final protected function getUpdateMediaItemMutationResolver(): UpdateMediaItemMutationResolver
    {
        if ($this->updateMediaItemMutationResolver === null) {
            /** @var UpdateMediaItemMutationResolver */
            $updateMediaItemMutationResolver = $this->instanceManager->getInstance(UpdateMediaItemMutationResolver::class);
            $this->updateMediaItemMutationResolver = $updateMediaItemMutationResolver;
        }
        return $this->updateMediaItemMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateMediaItemMutationResolver();
    }
}
