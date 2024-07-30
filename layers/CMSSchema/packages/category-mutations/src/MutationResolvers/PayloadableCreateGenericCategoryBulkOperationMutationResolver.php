<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableCreateGenericCategoryBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableCreateGenericCategoryMutationResolver $payloadableCreateGenericCategoryMutationResolver = null;

    final public function setPayloadableCreateGenericCategoryMutationResolver(PayloadableCreateGenericCategoryMutationResolver $payloadableCreateGenericCategoryMutationResolver): void
    {
        $this->payloadableCreateGenericCategoryMutationResolver = $payloadableCreateGenericCategoryMutationResolver;
    }
    final protected function getPayloadableCreateGenericCategoryMutationResolver(): PayloadableCreateGenericCategoryMutationResolver
    {
        if ($this->payloadableCreateGenericCategoryMutationResolver === null) {
            /** @var PayloadableCreateGenericCategoryMutationResolver */
            $payloadableCreateGenericCategoryMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericCategoryMutationResolver::class);
            $this->payloadableCreateGenericCategoryMutationResolver = $payloadableCreateGenericCategoryMutationResolver;
        }
        return $this->payloadableCreateGenericCategoryMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableCreateGenericCategoryMutationResolver();
    }
}
