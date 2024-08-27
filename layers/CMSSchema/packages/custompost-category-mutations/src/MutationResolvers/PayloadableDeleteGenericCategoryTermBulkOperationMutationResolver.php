<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableDeleteGenericCategoryTermBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableDeleteGenericCategoryTermMutationResolver $payloadableDeleteGenericCategoryTermMutationResolver = null;

    final public function setPayloadableDeleteGenericCategoryTermMutationResolver(PayloadableDeleteGenericCategoryTermMutationResolver $payloadableDeleteGenericCategoryTermMutationResolver): void
    {
        $this->payloadableDeleteGenericCategoryTermMutationResolver = $payloadableDeleteGenericCategoryTermMutationResolver;
    }
    final protected function getPayloadableDeleteGenericCategoryTermMutationResolver(): PayloadableDeleteGenericCategoryTermMutationResolver
    {
        if ($this->payloadableDeleteGenericCategoryTermMutationResolver === null) {
            /** @var PayloadableDeleteGenericCategoryTermMutationResolver */
            $payloadableDeleteGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericCategoryTermMutationResolver::class);
            $this->payloadableDeleteGenericCategoryTermMutationResolver = $payloadableDeleteGenericCategoryTermMutationResolver;
        }
        return $this->payloadableDeleteGenericCategoryTermMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableDeleteGenericCategoryTermMutationResolver();
    }
}
