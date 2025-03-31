<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeleteTagTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeleteTagTermMetaMutationResolver $payloadableDeleteTagTermMetaMutationResolver = null;

    final protected function getPayloadableDeleteTagTermMetaMutationResolver(): PayloadableDeleteTagTermMetaMutationResolver
    {
        if ($this->payloadableDeleteTagTermMetaMutationResolver === null) {
            /** @var PayloadableDeleteTagTermMetaMutationResolver */
            $payloadableDeleteTagTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteTagTermMetaMutationResolver::class);
            $this->payloadableDeleteTagTermMetaMutationResolver = $payloadableDeleteTagTermMetaMutationResolver;
        }
        return $this->payloadableDeleteTagTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeleteTagTermMetaMutationResolver();
    }
}
