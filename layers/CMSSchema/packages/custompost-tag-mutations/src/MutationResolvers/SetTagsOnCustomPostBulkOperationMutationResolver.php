<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\SchemaCommons\MutationResolvers\AbstractBulkOperationDecoratorMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

class SetTagsOnCustomPostBulkOperationMutationResolver extends AbstractBulkOperationDecoratorMutationResolver
{
    private ?SetTagsOnCustomPostMutationResolver $setTagsOnCustomPostMutationResolver = null;

    final protected function getSetTagsOnCustomPostMutationResolver(): SetTagsOnCustomPostMutationResolver
    {
        if ($this->setTagsOnCustomPostMutationResolver === null) {
            /** @var SetTagsOnCustomPostMutationResolver */
            $setTagsOnCustomPostMutationResolver = $this->instanceManager->getInstance(SetTagsOnCustomPostMutationResolver::class);
            $this->setTagsOnCustomPostMutationResolver = $setTagsOnCustomPostMutationResolver;
        }
        return $this->setTagsOnCustomPostMutationResolver;
    }

    protected function getDecoratedOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetTagsOnCustomPostMutationResolver();
    }
}
