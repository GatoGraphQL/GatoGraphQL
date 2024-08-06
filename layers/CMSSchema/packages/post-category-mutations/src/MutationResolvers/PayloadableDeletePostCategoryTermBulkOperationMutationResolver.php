<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeletePostCategoryTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeletePostCategoryTermMutationResolver $payloadableDeletePostCategoryTermMutationResolver = null;

    final public function setPayloadableDeletePostCategoryTermMutationResolver(PayloadableDeletePostCategoryTermMutationResolver $payloadableDeletePostCategoryTermMutationResolver): void
    {
        $this->payloadableDeletePostCategoryTermMutationResolver = $payloadableDeletePostCategoryTermMutationResolver;
    }
    final protected function getPayloadableDeletePostCategoryTermMutationResolver(): PayloadableDeletePostCategoryTermMutationResolver
    {
        if ($this->payloadableDeletePostCategoryTermMutationResolver === null) {
            /** @var PayloadableDeletePostCategoryTermMutationResolver */
            $payloadableDeletePostCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableDeletePostCategoryTermMutationResolver::class);
            $this->payloadableDeletePostCategoryTermMutationResolver = $payloadableDeletePostCategoryTermMutationResolver;
        }
        return $this->payloadableDeletePostCategoryTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeletePostCategoryTermMutationResolver();
    }
}
