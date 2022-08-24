<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\HighlightMutations\MutationResolvers\CreateHighlightMutationResolver;

class CreateHighlightMutationResolverBridge extends AbstractCreateUpdateHighlightMutationResolverBridge
{
    private ?CreateHighlightMutationResolver $createHighlightMutationResolver = null;

    final public function setCreateHighlightMutationResolver(CreateHighlightMutationResolver $createHighlightMutationResolver): void
    {
        $this->createHighlightMutationResolver = $createHighlightMutationResolver;
    }
    final protected function getCreateHighlightMutationResolver(): CreateHighlightMutationResolver
    {
        /** @var CreateHighlightMutationResolver */
        return $this->createHighlightMutationResolver ??= $this->instanceManager->getInstance(CreateHighlightMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateHighlightMutationResolver();
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
