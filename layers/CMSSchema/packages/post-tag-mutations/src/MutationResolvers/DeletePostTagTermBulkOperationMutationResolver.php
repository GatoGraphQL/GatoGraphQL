<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeletePostTagTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeletePostTagTermMutationResolver $deletePostTagTermMutationResolver = null;

    final protected function getDeletePostTagTermMutationResolver(): DeletePostTagTermMutationResolver
    {
        if ($this->deletePostTagTermMutationResolver === null) {
            /** @var DeletePostTagTermMutationResolver */
            $deletePostTagTermMutationResolver = $this->instanceManager->getInstance(DeletePostTagTermMutationResolver::class);
            $this->deletePostTagTermMutationResolver = $deletePostTagTermMutationResolver;
        }
        return $this->deletePostTagTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeletePostTagTermMutationResolver();
    }
}
