<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateCustomPostMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateCustomPostMetaMutationResolver $updateCustomPostMetaMutationResolver = null;

    final protected function getUpdateCustomPostMetaMutationResolver(): UpdateCustomPostMetaMutationResolver
    {
        if ($this->updateCustomPostMetaMutationResolver === null) {
            /** @var UpdateCustomPostMetaMutationResolver */
            $updateCustomPostMetaMutationResolver = $this->instanceManager->getInstance(UpdateCustomPostMetaMutationResolver::class);
            $this->updateCustomPostMetaMutationResolver = $updateCustomPostMetaMutationResolver;
        }
        return $this->updateCustomPostMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateCustomPostMetaMutationResolver();
    }
}
