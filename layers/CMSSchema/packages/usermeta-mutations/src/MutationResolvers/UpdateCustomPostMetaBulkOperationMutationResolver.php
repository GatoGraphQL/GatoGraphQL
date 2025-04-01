<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateUserMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateUserMetaMutationResolver $updateUserMetaMutationResolver = null;

    final protected function getUpdateUserMetaMutationResolver(): UpdateUserMetaMutationResolver
    {
        if ($this->updateUserMetaMutationResolver === null) {
            /** @var UpdateUserMetaMutationResolver */
            $updateUserMetaMutationResolver = $this->instanceManager->getInstance(UpdateUserMetaMutationResolver::class);
            $this->updateUserMetaMutationResolver = $updateUserMetaMutationResolver;
        }
        return $this->updateUserMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateUserMetaMutationResolver();
    }
}
