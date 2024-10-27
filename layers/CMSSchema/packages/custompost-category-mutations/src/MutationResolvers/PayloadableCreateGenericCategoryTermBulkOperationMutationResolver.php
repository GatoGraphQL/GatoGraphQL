<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableCreateGenericCategoryTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableCreateGenericCategoryTermMutationResolver $payloadableCreateGenericCategoryTermMutationResolver = null;

    final protected function getPayloadableCreateGenericCategoryTermMutationResolver(): PayloadableCreateGenericCategoryTermMutationResolver
    {
        if ($this->payloadableCreateGenericCategoryTermMutationResolver === null) {
            /** @var PayloadableCreateGenericCategoryTermMutationResolver */
            $payloadableCreateGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericCategoryTermMutationResolver::class);
            $this->payloadableCreateGenericCategoryTermMutationResolver = $payloadableCreateGenericCategoryTermMutationResolver;
        }
        return $this->payloadableCreateGenericCategoryTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableCreateGenericCategoryTermMutationResolver();
    }
}
