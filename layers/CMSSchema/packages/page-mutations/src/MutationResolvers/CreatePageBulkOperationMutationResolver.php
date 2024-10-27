<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class CreatePageBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?CreatePageMutationResolver $createPageMutationResolver = null;

    final protected function getCreatePageMutationResolver(): CreatePageMutationResolver
    {
        if ($this->createPageMutationResolver === null) {
            /** @var CreatePageMutationResolver */
            $createPageMutationResolver = $this->instanceManager->getInstance(CreatePageMutationResolver::class);
            $this->createPageMutationResolver = $createPageMutationResolver;
        }
        return $this->createPageMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getCreatePageMutationResolver();
    }
}
