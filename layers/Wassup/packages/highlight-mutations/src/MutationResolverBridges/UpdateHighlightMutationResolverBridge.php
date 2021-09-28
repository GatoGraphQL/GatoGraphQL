<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\HighlightMutations\MutationResolvers\UpdateHighlightMutationResolver;

class UpdateHighlightMutationResolverBridge extends AbstractCreateUpdateHighlightMutationResolverBridge
{
    protected UpdateHighlightMutationResolver $updateHighlightMutationResolver;

    #[Required]
    public function autowireUpdateHighlightMutationResolverBridge(
        UpdateHighlightMutationResolver $updateHighlightMutationResolver,
    ) {
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
