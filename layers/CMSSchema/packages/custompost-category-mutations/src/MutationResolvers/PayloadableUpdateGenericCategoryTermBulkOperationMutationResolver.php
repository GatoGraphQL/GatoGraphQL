<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateGenericCategoryTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateGenericCategoryTermMutationResolver $payloadableUpdateGenericCategoryTermMutationResolver = null;

    final protected function getPayloadableUpdateGenericCategoryTermMutationResolver(): PayloadableUpdateGenericCategoryTermMutationResolver
    {
        if ($this->payloadableUpdateGenericCategoryTermMutationResolver === null) {
            /** @var PayloadableUpdateGenericCategoryTermMutationResolver */
            $payloadableUpdateGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCategoryTermMutationResolver::class);
            $this->payloadableUpdateGenericCategoryTermMutationResolver = $payloadableUpdateGenericCategoryTermMutationResolver;
        }
        return $this->payloadableUpdateGenericCategoryTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateGenericCategoryTermMutationResolver();
    }
}
