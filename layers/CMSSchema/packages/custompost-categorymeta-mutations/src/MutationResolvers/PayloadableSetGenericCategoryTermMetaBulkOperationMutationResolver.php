<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableSetGenericCategoryTermMetaBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableSetGenericCategoryTermMetaMutationResolver $payloadableSetGenericCategoryTermMetaMutationResolver = null;

    final protected function getPayloadableSetGenericCategoryTermMetaMutationResolver(): PayloadableSetGenericCategoryTermMetaMutationResolver
    {
        if ($this->payloadableSetGenericCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableSetGenericCategoryTermMetaMutationResolver */
            $payloadableSetGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableSetGenericCategoryTermMetaMutationResolver::class);
            $this->payloadableSetGenericCategoryTermMetaMutationResolver = $payloadableSetGenericCategoryTermMetaMutationResolver;
        }
        return $this->payloadableSetGenericCategoryTermMetaMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetGenericCategoryTermMetaMutationResolver();
    }
}
