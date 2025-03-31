<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class SetCustomPostMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?SetCustomPostMetaMutationResolver $setCustomPostMetaMutationResolver = null;

    final protected function getSetCustomPostMetaMutationResolver(): SetCustomPostMetaMutationResolver
    {
        if ($this->setCustomPostMetaMutationResolver === null) {
            /** @var SetCustomPostMetaMutationResolver */
            $setCustomPostMetaMutationResolver = $this->instanceManager->getInstance(SetCustomPostMetaMutationResolver::class);
            $this->setCustomPostMetaMutationResolver = $setCustomPostMetaMutationResolver;
        }
        return $this->setCustomPostMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetCustomPostMetaMutationResolver();
    }
}
