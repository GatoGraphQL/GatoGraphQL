<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class UpdateTagTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?UpdateTagTermMetaMutationResolver $updateTagTermMetaMutationResolver = null;

    final protected function getUpdateTagTermMetaMutationResolver(): UpdateTagTermMetaMutationResolver
    {
        if ($this->updateTagTermMetaMutationResolver === null) {
            /** @var UpdateTagTermMetaMutationResolver */
            $updateTagTermMetaMutationResolver = $this->instanceManager->getInstance(UpdateTagTermMetaMutationResolver::class);
            $this->updateTagTermMetaMutationResolver = $updateTagTermMetaMutationResolver;
        }
        return $this->updateTagTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateTagTermMetaMutationResolver();
    }
}
