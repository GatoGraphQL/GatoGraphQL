<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class AddUserMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?AddUserMetaMutationResolver $addUserMetaMutationResolver = null;

    final protected function getAddUserMetaMutationResolver(): AddUserMetaMutationResolver
    {
        if ($this->addUserMetaMutationResolver === null) {
            /** @var AddUserMetaMutationResolver */
            $addUserMetaMutationResolver = $this->instanceManager->getInstance(AddUserMetaMutationResolver::class);
            $this->addUserMetaMutationResolver = $addUserMetaMutationResolver;
        }
        return $this->addUserMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getAddUserMetaMutationResolver();
    }
}
