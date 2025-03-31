<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class AddCustomPostMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?AddCustomPostMetaMutationResolver $addCustomPostMetaMutationResolver = null;

    final protected function getAddCustomPostMetaMutationResolver(): AddCustomPostMetaMutationResolver
    {
        if ($this->addCustomPostMetaMutationResolver === null) {
            /** @var AddCustomPostMetaMutationResolver */
            $addCustomPostMetaMutationResolver = $this->instanceManager->getInstance(AddCustomPostMetaMutationResolver::class);
            $this->addCustomPostMetaMutationResolver = $addCustomPostMetaMutationResolver;
        }
        return $this->addCustomPostMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getAddCustomPostMetaMutationResolver();
    }
}
