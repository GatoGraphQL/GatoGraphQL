<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeleteUserBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeleteUserMutationResolver $deleteUserMutationResolver = null;

    final protected function getDeleteUserMutationResolver(): DeleteUserMutationResolver
    {
        if ($this->deleteUserMutationResolver === null) {
            /** @var DeleteUserMutationResolver */
            $deleteUserMutationResolver = $this->instanceManager->getInstance(DeleteUserMutationResolver::class);
            $this->deleteUserMutationResolver = $deleteUserMutationResolver;
        }
        return $this->deleteUserMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeleteUserMutationResolver();
    }
}
