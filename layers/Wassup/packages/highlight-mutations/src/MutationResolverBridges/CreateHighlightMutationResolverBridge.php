<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolverBridges;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\HighlightMutations\MutationResolvers\CreateHighlightMutationResolver;

class CreateHighlightMutationResolverBridge extends AbstractCreateUpdateHighlightMutationResolverBridge
{
    protected CreateHighlightMutationResolver $createHighlightMutationResolver;
    
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function autowireCreateHighlightMutationResolverBridge(
        CreateHighlightMutationResolver $createHighlightMutationResolver,
    ) {
        $this->createHighlightMutationResolver = $createHighlightMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->createHighlightMutationResolver;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
