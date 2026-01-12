<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateMenuBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateMenuMutationResolver $updateMenuMutationResolver = null;

    final protected function getUpdateMenuMutationResolver(): UpdateMenuMutationResolver
    {
        if ($this->updateMenuMutationResolver === null) {
            /** @var UpdateMenuMutationResolver */
            $updateMenuMutationResolver = $this->instanceManager->getInstance(UpdateMenuMutationResolver::class);
            $this->updateMenuMutationResolver = $updateMenuMutationResolver;
        }
        return $this->updateMenuMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateMenuMutationResolver();
    }
}
