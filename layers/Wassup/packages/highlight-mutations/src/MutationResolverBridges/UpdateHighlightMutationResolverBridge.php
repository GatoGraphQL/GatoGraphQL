<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\HighlightMutations\MutationResolvers\UpdateHighlightMutationResolver;

class UpdateHighlightMutationResolverBridge extends AbstractCreateUpdateHighlightMutationResolverBridge
{
    private ?UpdateHighlightMutationResolver $updateHighlightMutationResolver = null;

    final public function setUpdateHighlightMutationResolver(UpdateHighlightMutationResolver $updateHighlightMutationResolver): void
    {
        $this->updateHighlightMutationResolver = $updateHighlightMutationResolver;
    }
    final protected function getUpdateHighlightMutationResolver(): UpdateHighlightMutationResolver
    {
        /** @var UpdateHighlightMutationResolver */
        return $this->updateHighlightMutationResolver ??= $this->instanceManager->getInstance(UpdateHighlightMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateHighlightMutationResolver();
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
