<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeleteCustomPostMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeleteCustomPostMetaMutationResolver $payloadableDeleteCustomPostMetaMutationResolver = null;

    final protected function getPayloadableDeleteCustomPostMetaMutationResolver(): PayloadableDeleteCustomPostMetaMutationResolver
    {
        if ($this->payloadableDeleteCustomPostMetaMutationResolver === null) {
            /** @var PayloadableDeleteCustomPostMetaMutationResolver */
            $payloadableDeleteCustomPostMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteCustomPostMetaMutationResolver::class);
            $this->payloadableDeleteCustomPostMetaMutationResolver = $payloadableDeleteCustomPostMetaMutationResolver;
        }
        return $this->payloadableDeleteCustomPostMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeleteCustomPostMetaMutationResolver();
    }
}
