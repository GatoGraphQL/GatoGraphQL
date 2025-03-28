<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateCategoryTermMetaMutationResolver $payloadableUpdateCategoryTermMetaMutationResolver = null;

    final protected function getPayloadableUpdateCategoryTermMetaMutationResolver(): PayloadableUpdateCategoryTermMetaMutationResolver
    {
        if ($this->payloadableUpdateCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableUpdateCategoryTermMetaMutationResolver */
            $payloadableUpdateCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateCategoryTermMetaMutationResolver::class);
            $this->payloadableUpdateCategoryTermMetaMutationResolver = $payloadableUpdateCategoryTermMetaMutationResolver;
        }
        return $this->payloadableUpdateCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateCategoryTermMetaMutationResolver();
    }
}
