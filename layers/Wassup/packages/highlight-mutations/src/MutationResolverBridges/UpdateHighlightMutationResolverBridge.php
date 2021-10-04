<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\HighlightMutations\MutationResolvers\UpdateHighlightMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UpdateHighlightMutationResolverBridge extends AbstractCreateUpdateHighlightMutationResolverBridge
{
    protected UpdateHighlightMutationResolver $updateHighlightMutationResolver;

    #[Required]
    final public function autowireUpdateHighlightMutationResolverBridge(
        UpdateHighlightMutationResolver $updateHighlightMutationResolver,
    ): void {
        $this->updateHighlightMutationResolver = $updateHighlightMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->updateHighlightMutationResolver;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
