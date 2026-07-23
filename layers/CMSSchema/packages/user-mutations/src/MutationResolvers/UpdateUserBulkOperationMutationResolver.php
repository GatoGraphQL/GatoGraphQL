<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateUserBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateUserMutationResolver $updateUserMutationResolver = null;

    final protected function getUpdateUserMutationResolver(): UpdateUserMutationResolver
    {
        if ($this->updateUserMutationResolver === null) {
            /** @var UpdateUserMutationResolver */
            $updateUserMutationResolver = $this->instanceManager->getInstance(UpdateUserMutationResolver::class);
            $this->updateUserMutationResolver = $updateUserMutationResolver;
        }
        return $this->updateUserMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateUserMutationResolver();
    }
}
