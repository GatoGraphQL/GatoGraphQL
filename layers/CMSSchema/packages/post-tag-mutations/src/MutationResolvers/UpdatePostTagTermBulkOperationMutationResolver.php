<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdatePostTagTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdatePostTagTermMutationResolver $updatePostTagTermMutationResolver = null;

    final protected function getUpdatePostTagTermMutationResolver(): UpdatePostTagTermMutationResolver
    {
        if ($this->updatePostTagTermMutationResolver === null) {
            /** @var UpdatePostTagTermMutationResolver */
            $updatePostTagTermMutationResolver = $this->instanceManager->getInstance(UpdatePostTagTermMutationResolver::class);
            $this->updatePostTagTermMutationResolver = $updatePostTagTermMutationResolver;
        }
        return $this->updatePostTagTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdatePostTagTermMutationResolver();
    }
}
