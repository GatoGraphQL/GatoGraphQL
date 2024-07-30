<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateGenericCategoryBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateGenericCategoryMutationResolver $payloadableUpdateGenericCategoryMutationResolver = null;

    final public function setPayloadableUpdateGenericCategoryMutationResolver(PayloadableUpdateGenericCategoryMutationResolver $payloadableUpdateGenericCategoryMutationResolver): void
    {
        $this->payloadableUpdateGenericCategoryMutationResolver = $payloadableUpdateGenericCategoryMutationResolver;
    }
    final protected function getPayloadableUpdateGenericCategoryMutationResolver(): PayloadableUpdateGenericCategoryMutationResolver
    {
        if ($this->payloadableUpdateGenericCategoryMutationResolver === null) {
            /** @var PayloadableUpdateGenericCategoryMutationResolver */
            $payloadableUpdateGenericCategoryMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCategoryMutationResolver::class);
            $this->payloadableUpdateGenericCategoryMutationResolver = $payloadableUpdateGenericCategoryMutationResolver;
        }
        return $this->payloadableUpdateGenericCategoryMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateGenericCategoryMutationResolver();
    }
}
