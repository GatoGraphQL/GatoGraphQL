<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class SetTagTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?SetTagTermMetaMutationResolver $setTagTermMetaMutationResolver = null;

    final protected function getSetTagTermMetaMutationResolver(): SetTagTermMetaMutationResolver
    {
        if ($this->setTagTermMetaMutationResolver === null) {
            /** @var SetTagTermMetaMutationResolver */
            $setTagTermMetaMutationResolver = $this->instanceManager->getInstance(SetTagTermMetaMutationResolver::class);
            $this->setTagTermMetaMutationResolver = $setTagTermMetaMutationResolver;
        }
        return $this->setTagTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetTagTermMetaMutationResolver();
    }
}
