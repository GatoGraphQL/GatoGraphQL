<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateGenericCustomPostBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateGenericCustomPostMutationResolver $updateGenericCustomPostMutationResolver = null;

    final protected function getUpdateGenericCustomPostMutationResolver(): UpdateGenericCustomPostMutationResolver
    {
        if ($this->updateGenericCustomPostMutationResolver === null) {
            /** @var UpdateGenericCustomPostMutationResolver */
            $updateGenericCustomPostMutationResolver = $this->instanceManager->getInstance(UpdateGenericCustomPostMutationResolver::class);
            $this->updateGenericCustomPostMutationResolver = $updateGenericCustomPostMutationResolver;
        }
        return $this->updateGenericCustomPostMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateGenericCustomPostMutationResolver();
    }
}
