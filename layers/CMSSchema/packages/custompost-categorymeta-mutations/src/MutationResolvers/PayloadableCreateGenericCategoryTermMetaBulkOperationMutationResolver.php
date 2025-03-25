<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableCreateGenericCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableCreateGenericCategoryTermMetaMutationResolver $payloadableCreateGenericCategoryTermMetaMutationResolver = null;

    final protected function getPayloadableCreateGenericCategoryTermMetaMutationResolver(): PayloadableCreateGenericCategoryTermMetaMutationResolver
    {
        if ($this->payloadableCreateGenericCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableCreateGenericCategoryTermMetaMutationResolver */
            $payloadableCreateGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericCategoryTermMetaMutationResolver::class);
            $this->payloadableCreateGenericCategoryTermMetaMutationResolver = $payloadableCreateGenericCategoryTermMetaMutationResolver;
        }
        return $this->payloadableCreateGenericCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableCreateGenericCategoryTermMetaMutationResolver();
    }
}
