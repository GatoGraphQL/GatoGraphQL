<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableCreatePostTagTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableCreatePostTagTermMutationResolver $payloadableCreatePostTagTermMutationResolver = null;

    final protected function getPayloadableCreatePostTagTermMutationResolver(): PayloadableCreatePostTagTermMutationResolver
    {
        if ($this->payloadableCreatePostTagTermMutationResolver === null) {
            /** @var PayloadableCreatePostTagTermMutationResolver */
            $payloadableCreatePostTagTermMutationResolver = $this->instanceManager->getInstance(PayloadableCreatePostTagTermMutationResolver::class);
            $this->payloadableCreatePostTagTermMutationResolver = $payloadableCreatePostTagTermMutationResolver;
        }
        return $this->payloadableCreatePostTagTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableCreatePostTagTermMutationResolver();
    }
}
