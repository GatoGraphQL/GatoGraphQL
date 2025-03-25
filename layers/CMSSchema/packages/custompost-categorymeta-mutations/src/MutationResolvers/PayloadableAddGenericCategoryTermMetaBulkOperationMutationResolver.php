<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableAddGenericCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableAddGenericCategoryTermMetaMutationResolver $payloadableAddGenericCategoryTermMetaMutationResolver = null;

    final protected function getPayloadableAddGenericCategoryTermMetaMutationResolver(): PayloadableAddGenericCategoryTermMetaMutationResolver
    {
        if ($this->payloadableAddGenericCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableAddGenericCategoryTermMetaMutationResolver */
            $payloadableAddGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddGenericCategoryTermMetaMutationResolver::class);
            $this->payloadableAddGenericCategoryTermMetaMutationResolver = $payloadableAddGenericCategoryTermMetaMutationResolver;
        }
        return $this->payloadableAddGenericCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableAddGenericCategoryTermMetaMutationResolver();
    }
}
