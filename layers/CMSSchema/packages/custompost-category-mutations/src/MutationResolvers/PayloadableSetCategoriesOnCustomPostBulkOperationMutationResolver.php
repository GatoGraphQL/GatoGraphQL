<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableSetCategoriesOnCustomPostBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableSetCategoriesOnCustomPostMutationResolver $payloadableSetCategoriesOnCustomPostMutationResolver = null;

    final protected function getPayloadableSetCategoriesOnCustomPostMutationResolver(): PayloadableSetCategoriesOnCustomPostMutationResolver
    {
        if ($this->payloadableSetCategoriesOnCustomPostMutationResolver === null) {
            /** @var PayloadableSetCategoriesOnCustomPostMutationResolver */
            $payloadableSetCategoriesOnCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableSetCategoriesOnCustomPostMutationResolver::class);
            $this->payloadableSetCategoriesOnCustomPostMutationResolver = $payloadableSetCategoriesOnCustomPostMutationResolver;
        }
        return $this->payloadableSetCategoriesOnCustomPostMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetCategoriesOnCustomPostMutationResolver();
    }
}
