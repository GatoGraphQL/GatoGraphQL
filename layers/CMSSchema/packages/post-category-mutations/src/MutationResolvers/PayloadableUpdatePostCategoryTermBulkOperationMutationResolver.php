<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdatePostCategoryTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdatePostCategoryTermMutationResolver $payloadableUpdatePostCategoryTermMutationResolver = null;

    final protected function getPayloadableUpdatePostCategoryTermMutationResolver(): PayloadableUpdatePostCategoryTermMutationResolver
    {
        if ($this->payloadableUpdatePostCategoryTermMutationResolver === null) {
            /** @var PayloadableUpdatePostCategoryTermMutationResolver */
            $payloadableUpdatePostCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableUpdatePostCategoryTermMutationResolver::class);
            $this->payloadableUpdatePostCategoryTermMutationResolver = $payloadableUpdatePostCategoryTermMutationResolver;
        }
        return $this->payloadableUpdatePostCategoryTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdatePostCategoryTermMutationResolver();
    }
}
