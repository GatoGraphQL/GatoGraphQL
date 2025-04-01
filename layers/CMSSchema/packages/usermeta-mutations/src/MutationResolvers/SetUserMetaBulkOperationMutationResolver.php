<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class SetUserMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?SetUserMetaMutationResolver $setUserMetaMutationResolver = null;

    final protected function getSetUserMetaMutationResolver(): SetUserMetaMutationResolver
    {
        if ($this->setUserMetaMutationResolver === null) {
            /** @var SetUserMetaMutationResolver */
            $setUserMetaMutationResolver = $this->instanceManager->getInstance(SetUserMetaMutationResolver::class);
            $this->setUserMetaMutationResolver = $setUserMetaMutationResolver;
        }
        return $this->setUserMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetUserMetaMutationResolver();
    }
}
