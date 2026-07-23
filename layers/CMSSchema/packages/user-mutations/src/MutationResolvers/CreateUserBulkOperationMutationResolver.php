<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class CreateUserBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?CreateUserMutationResolver $createUserMutationResolver = null;

    final protected function getCreateUserMutationResolver(): CreateUserMutationResolver
    {
        if ($this->createUserMutationResolver === null) {
            /** @var CreateUserMutationResolver */
            $createUserMutationResolver = $this->instanceManager->getInstance(CreateUserMutationResolver::class);
            $this->createUserMutationResolver = $createUserMutationResolver;
        }
        return $this->createUserMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateUserMutationResolver();
    }
}
