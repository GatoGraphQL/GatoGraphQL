<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableAddTagTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableAddTagTermMetaMutationResolver $payloadableAddTagTermMetaMutationResolver = null;

    final protected function getPayloadableAddTagTermMetaMutationResolver(): PayloadableAddTagTermMetaMutationResolver
    {
        if ($this->payloadableAddTagTermMetaMutationResolver === null) {
            /** @var PayloadableAddTagTermMetaMutationResolver */
            $payloadableAddTagTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddTagTermMetaMutationResolver::class);
            $this->payloadableAddTagTermMetaMutationResolver = $payloadableAddTagTermMetaMutationResolver;
        }
        return $this->payloadableAddTagTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableAddTagTermMetaMutationResolver();
    }
}
