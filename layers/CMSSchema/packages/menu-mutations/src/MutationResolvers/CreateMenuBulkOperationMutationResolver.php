<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class CreateMenuBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?CreateMenuMutationResolver $createMenuMutationResolver = null;

    final protected function getCreateMenuMutationResolver(): CreateMenuMutationResolver
    {
        if ($this->createMenuMutationResolver === null) {
            /** @var CreateMenuMutationResolver */
            $createMenuMutationResolver = $this->instanceManager->getInstance(CreateMenuMutationResolver::class);
            $this->createMenuMutationResolver = $createMenuMutationResolver;
        }
        return $this->createMenuMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateMenuMutationResolver();
    }
}
