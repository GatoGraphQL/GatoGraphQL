<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeleteGenericCustomPostBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeleteGenericCustomPostMutationResolver $deleteGenericCustomPostMutationResolver = null;

    final protected function getDeleteGenericCustomPostMutationResolver(): DeleteGenericCustomPostMutationResolver
    {
        if ($this->deleteGenericCustomPostMutationResolver === null) {
            /** @var DeleteGenericCustomPostMutationResolver */
            $deleteGenericCustomPostMutationResolver = $this->instanceManager->getInstance(DeleteGenericCustomPostMutationResolver::class);
            $this->deleteGenericCustomPostMutationResolver = $deleteGenericCustomPostMutationResolver;
        }
        return $this->deleteGenericCustomPostMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeleteGenericCustomPostMutationResolver();
    }
}
