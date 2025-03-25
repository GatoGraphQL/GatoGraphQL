<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableUpdateGenericCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableUpdateGenericCategoryTermMetaMutationResolver $payloadableUpdateGenericCategoryTermMetaMutationResolver = null;

    final protected function getPayloadableUpdateGenericCategoryTermMetaMutationResolver(): PayloadableUpdateGenericCategoryTermMetaMutationResolver
    {
        if ($this->payloadableUpdateGenericCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableUpdateGenericCategoryTermMetaMutationResolver */
            $payloadableUpdateGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCategoryTermMetaMutationResolver::class);
            $this->payloadableUpdateGenericCategoryTermMetaMutationResolver = $payloadableUpdateGenericCategoryTermMetaMutationResolver;
        }
        return $this->payloadableUpdateGenericCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableUpdateGenericCategoryTermMetaMutationResolver();
    }
}
