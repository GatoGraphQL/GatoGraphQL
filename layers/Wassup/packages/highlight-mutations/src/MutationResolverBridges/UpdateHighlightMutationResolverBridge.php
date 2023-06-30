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
        if ($this->updateHighlightMutationResolver === null) {
            /** @var UpdateHighlightMutationResolver */
            $updateHighlightMutationResolver = $this->instanceManager->getInstance(UpdateHighlightMutationResolver::class);
            $this->updateHighlightMutationResolver = $updateHighlightMutationResolver;
        }
        return $this->updateHighlightMutationResolver;
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
