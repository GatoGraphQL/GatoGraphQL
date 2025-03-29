<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableSetCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableSetCategoryTermMetaMutationResolver $payloadableSetCategoryTermMetaMutationResolver = null;

    final protected function getPayloadableSetCategoryTermMetaMutationResolver(): PayloadableSetCategoryTermMetaMutationResolver
    {
        if ($this->payloadableSetCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableSetCategoryTermMetaMutationResolver */
            $payloadableSetCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableSetCategoryTermMetaMutationResolver::class);
            $this->payloadableSetCategoryTermMetaMutationResolver = $payloadableSetCategoryTermMetaMutationResolver;
        }
        return $this->payloadableSetCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetCategoryTermMetaMutationResolver();
    }
}
