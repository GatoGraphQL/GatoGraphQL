<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class DeleteCustomPostMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?DeleteCustomPostMetaMutationResolver $deleteCustomPostMetaMutationResolver = null;

    final protected function getDeleteCustomPostMetaMutationResolver(): DeleteCustomPostMetaMutationResolver
    {
        if ($this->deleteCustomPostMetaMutationResolver === null) {
            /** @var DeleteCustomPostMetaMutationResolver */
            $deleteCustomPostMetaMutationResolver = $this->instanceManager->getInstance(DeleteCustomPostMetaMutationResolver::class);
            $this->deleteCustomPostMetaMutationResolver = $deleteCustomPostMetaMutationResolver;
        }
        return $this->deleteCustomPostMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getDeleteCustomPostMetaMutationResolver();
    }
}
