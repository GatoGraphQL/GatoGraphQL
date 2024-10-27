<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableCreatePostCategoryTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableCreatePostCategoryTermMutationResolver $payloadableCreatePostCategoryTermMutationResolver = null;

    final protected function getPayloadableCreatePostCategoryTermMutationResolver(): PayloadableCreatePostCategoryTermMutationResolver
    {
        if ($this->payloadableCreatePostCategoryTermMutationResolver === null) {
            /** @var PayloadableCreatePostCategoryTermMutationResolver */
            $payloadableCreatePostCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableCreatePostCategoryTermMutationResolver::class);
            $this->payloadableCreatePostCategoryTermMutationResolver = $payloadableCreatePostCategoryTermMutationResolver;
        }
        return $this->payloadableCreatePostCategoryTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableCreatePostCategoryTermMutationResolver();
    }
}
