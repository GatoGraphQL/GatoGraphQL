<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeleteUserMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeleteUserMetaMutationResolver $deleteUserMetaMutationResolver = null;

    final protected function getDeleteUserMetaMutationResolver(): DeleteUserMetaMutationResolver
    {
        if ($this->deleteUserMetaMutationResolver === null) {
            /** @var DeleteUserMetaMutationResolver */
            $deleteUserMetaMutationResolver = $this->instanceManager->getInstance(DeleteUserMetaMutationResolver::class);
            $this->deleteUserMetaMutationResolver = $deleteUserMetaMutationResolver;
        }
        return $this->deleteUserMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeleteUserMetaMutationResolver();
    }
}
