<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableAddCustomPostMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableAddCustomPostMetaMutationResolver $payloadableAddCustomPostMetaMutationResolver = null;

    final protected function getPayloadableAddCustomPostMetaMutationResolver(): PayloadableAddCustomPostMetaMutationResolver
    {
        if ($this->payloadableAddCustomPostMetaMutationResolver === null) {
            /** @var PayloadableAddCustomPostMetaMutationResolver */
            $payloadableAddCustomPostMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddCustomPostMetaMutationResolver::class);
            $this->payloadableAddCustomPostMetaMutationResolver = $payloadableAddCustomPostMetaMutationResolver;
        }
        return $this->payloadableAddCustomPostMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableAddCustomPostMetaMutationResolver();
    }
}
