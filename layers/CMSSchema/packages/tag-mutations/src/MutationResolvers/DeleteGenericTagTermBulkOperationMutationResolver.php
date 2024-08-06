<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeleteGenericTagTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeleteGenericTagTermMutationResolver $deleteGenericTagTermMutationResolver = null;

    final public function setDeleteGenericTagTermMutationResolver(DeleteGenericTagTermMutationResolver $deleteGenericTagTermMutationResolver): void
    {
        $this->deleteGenericTagTermMutationResolver = $deleteGenericTagTermMutationResolver;
    }
    final protected function getDeleteGenericTagTermMutationResolver(): DeleteGenericTagTermMutationResolver
    {
        if ($this->deleteGenericTagTermMutationResolver === null) {
            /** @var DeleteGenericTagTermMutationResolver */
            $deleteGenericTagTermMutationResolver = $this->instanceManager->getInstance(DeleteGenericTagTermMutationResolver::class);
            $this->deleteGenericTagTermMutationResolver = $deleteGenericTagTermMutationResolver;
        }
        return $this->deleteGenericTagTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeleteGenericTagTermMutationResolver();
    }
}
