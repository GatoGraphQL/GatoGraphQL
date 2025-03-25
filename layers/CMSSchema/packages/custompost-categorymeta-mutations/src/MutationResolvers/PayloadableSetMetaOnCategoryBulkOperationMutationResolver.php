<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableSetMetaOnCategoryBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableSetMetaOnCategoryMutationResolver $payloadableSetMetaOnCategoryMutationResolver = null;

    final protected function getPayloadableSetMetaOnCategoryMutationResolver(): PayloadableSetMetaOnCategoryMutationResolver
    {
        if ($this->payloadableSetMetaOnCategoryMutationResolver === null) {
            /** @var PayloadableSetMetaOnCategoryMutationResolver */
            $payloadableSetMetaOnCategoryMutationResolver = $this->instanceManager->getInstance(PayloadableSetMetaOnCategoryMutationResolver::class);
            $this->payloadableSetMetaOnCategoryMutationResolver = $payloadableSetMetaOnCategoryMutationResolver;
        }
        return $this->payloadableSetMetaOnCategoryMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetMetaOnCategoryMutationResolver();
    }
}
