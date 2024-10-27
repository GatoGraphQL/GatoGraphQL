<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class PayloadableSetFeaturedImageOnCustomPostBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?PayloadableSetFeaturedImageOnCustomPostMutationResolver $payloadableSetFeaturedImageOnCustomPostMutationResolver = null;

    final protected function getPayloadableSetFeaturedImageOnCustomPostMutationResolver(): PayloadableSetFeaturedImageOnCustomPostMutationResolver
    {
        if ($this->payloadableSetFeaturedImageOnCustomPostMutationResolver === null) {
            /** @var PayloadableSetFeaturedImageOnCustomPostMutationResolver */
            $payloadableSetFeaturedImageOnCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableSetFeaturedImageOnCustomPostMutationResolver::class);
            $this->payloadableSetFeaturedImageOnCustomPostMutationResolver = $payloadableSetFeaturedImageOnCustomPostMutationResolver;
        }
        return $this->payloadableSetFeaturedImageOnCustomPostMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetFeaturedImageOnCustomPostMutationResolver();
    }
}
